<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostVersion;
use Illuminate\Support\Facades\Auth;

class VersionControlService
{
    /**
     * Create a new version of a post
     */
    public function createVersion(Post $post, ?string $changeSummary = null, bool $isMajorChange = false): PostVersion
    {
        $versionNumber = $post->getCurrentVersionNumber() + 1;
        $changes = $this->detectChanges($post);

        return PostVersion::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'version_number' => $versionNumber,
            'title' => $post->title,
            'content' => $post->content,
            'excerpt' => $post->excerpt,
            'meta_description' => $post->meta_description,
            'keywords' => $post->keywords,
            'change_summary' => $changeSummary ?? $this->generateChangeSummary($changes),
            'is_major_change' => $isMajorChange,
            'changes_made' => $changes,
        ]);
    }

    /**
     * Detect what changed in the post
     */
    protected function detectChanges(Post $post): array
    {
        $changes = [];
        $dirtyFields = $post->getDirty();

        $trackedFields = ['title', 'content', 'excerpt', 'meta_description', 'keywords'];

        foreach ($trackedFields as $field) {
            if (isset($dirtyFields[$field])) {
                $changes[$field] = [
                    'old' => $post->getOriginal($field),
                    'new' => $dirtyFields[$field],
                ];
            }
        }

        return $changes;
    }

    /**
     * Generate automatic change summary
     */
    protected function generateChangeSummary(array $changes): string
    {
        if (empty($changes)) {
            return 'No changes detected';
        }

        $changedFields = array_keys($changes);
        $fieldNames = array_map(function($field) {
            return ucfirst(str_replace('_', ' ', $field));
        }, $changedFields);

        return 'Updated: ' . implode(', ', $fieldNames);
    }

    /**
     * Restore a post to a previous version
     */
    public function restoreVersion(Post $post, PostVersion $version): void
    {
        // Save current state as a new version first
        $this->createVersion($post, "Restored to version {$version->version_number}", true);

        // Restore the old version
        $post->update([
            'title' => $version->title,
            'content' => $version->content,
            'excerpt' => $version->excerpt,
            'meta_description' => $version->meta_description,
            'keywords' => $version->keywords,
        ]);
    }

    /**
     * Compare two versions
     */
    public function compareVersions(PostVersion $version1, PostVersion $version2): array
    {
        $fields = ['title', 'content', 'excerpt', 'meta_description', 'keywords'];
        $differences = [];

        foreach ($fields as $field) {
            if ($version1->$field !== $version2->$field) {
                $differences[$field] = [
                    'old' => $version1->$field,
                    'new' => $version2->$field,
                ];
            }
        }

        return $differences;
    }

    /**
     * Clean up old versions (keep last N versions)
     */
    public function cleanOldVersions(Post $post, int $keepCount = 10): int
    {
        $versions = $post->versions()->orderBy('version_number', 'desc')->get();

        if ($versions->count() <= $keepCount) {
            return 0;
        }

        $versionsToDelete = $versions->slice($keepCount);
        $deletedCount = 0;

        foreach ($versionsToDelete as $version) {
            // Keep major changes
            if (!$version->is_major_change) {
                $version->delete();
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    /**
     * Check if post needs versioning (significant changes)
     */
    public function shouldCreateVersion(Post $post): bool
    {
        if (!$post->exists) {
            return false; // Don't version on create
        }

        $significantFields = ['title', 'content', 'meta_description', 'keywords'];
        
        foreach ($significantFields as $field) {
            if ($post->isDirty($field)) {
                return true;
            }
        }

        return false;
    }
}
