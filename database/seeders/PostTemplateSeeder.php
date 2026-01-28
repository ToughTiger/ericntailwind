<?php

namespace Database\Seeders;

use App\Models\PostTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostTemplateSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Tutorial / How-To Guide',
                'slug' => 'tutorial-how-to',
                'description' => 'Step-by-step tutorial for teaching a specific skill or process',
                'icon' => 'heroicon-o-academic-cap',
                'color' => 'info',
                'default_title_pattern' => 'How to [Topic]: A Complete Guide',
                'content_template' => $this->getTutorialTemplate(),
                'default_excerpt' => 'Learn how to [topic] with this comprehensive step-by-step guide. Perfect for beginners and advanced users alike.',
                'default_meta_description' => 'Complete guide on [topic]. Learn step-by-step with examples, best practices, and expert tips.',
                'default_keywords' => 'tutorial, how-to, guide, step-by-step, learn',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Product/Service Review',
                'slug' => 'product-review',
                'description' => 'Detailed review of a product, service, or tool',
                'icon' => 'heroicon-o-star',
                'color' => 'warning',
                'default_title_pattern' => '[Product Name] Review: [Year]',
                'content_template' => $this->getReviewTemplate(),
                'default_excerpt' => 'An in-depth review of [product], covering features, pros, cons, pricing, and our final verdict.',
                'default_meta_description' => 'Comprehensive [product] review. Features, pricing, pros & cons, and real-world testing results.',
                'default_keywords' => 'review, comparison, features, pricing, pros and cons',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Case Study',
                'slug' => 'case-study',
                'description' => 'Real-world example demonstrating results and insights',
                'icon' => 'heroicon-o-chart-bar',
                'color' => 'success',
                'default_title_pattern' => 'Case Study: How [Company/Person] Achieved [Result]',
                'content_template' => $this->getCaseStudyTemplate(),
                'default_excerpt' => 'Discover how [subject] achieved [results] using [method/tool]. Detailed case study with actionable insights.',
                'default_meta_description' => 'Real case study showing how [subject] achieved [specific results]. Includes strategies, metrics, and lessons learned.',
                'default_keywords' => 'case study, results, success story, example, real-world',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'News/Update Article',
                'slug' => 'news-update',
                'description' => 'Timely news or announcement post',
                'icon' => 'heroicon-o-newspaper',
                'color' => 'danger',
                'default_title_pattern' => '[Topic]: What You Need to Know',
                'content_template' => $this->getNewsTemplate(),
                'default_excerpt' => 'Breaking: [news summary]. Here\'s what you need to know and what it means for you.',
                'default_meta_description' => 'Latest news on [topic]. Key facts, analysis, and what it means for [audience].',
                'default_keywords' => 'news, update, announcement, breaking, latest',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Listicle',
                'slug' => 'listicle',
                'description' => 'List-based article (Top 10, Best X, etc.)',
                'icon' => 'heroicon-o-list-bullet',
                'color' => 'primary',
                'default_title_pattern' => '[Number] Best [Things] for [Purpose] in [Year]',
                'content_template' => $this->getListicleTemplate(),
                'default_excerpt' => 'Discover the top [number] [things] that [benefit]. Curated list with detailed analysis of each option.',
                'default_meta_description' => 'Top [number] [things] for [purpose]. Comprehensive comparison, features, and recommendations.',
                'default_keywords' => 'best, top, list, comparison, ranked',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Opinion/Editorial',
                'slug' => 'opinion-editorial',
                'description' => 'Thought leadership and opinion piece',
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'color' => 'gray',
                'default_title_pattern' => 'Why [Opinion/Stance] on [Topic]',
                'content_template' => $this->getOpinionTemplate(),
                'default_excerpt' => 'My take on [topic] and why [stance]. A deep dive into [key points].',
                'default_meta_description' => 'Opinion on [topic]: Why [stance]. Analysis, arguments, and implications.',
                'default_keywords' => 'opinion, analysis, perspective, commentary, editorial',
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            PostTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }

    private function getTutorialTemplate(): string
    {
        return <<<'MD'
## Introduction

[Brief introduction to what readers will learn and why it matters]

## What You'll Need

- Prerequisite 1
- Prerequisite 2
- Tool/Software needed

## Step 1: [First Major Step]

[Detailed explanation of the first step]

```code
// Example code if applicable
```

## Step 2: [Second Major Step]

[Detailed explanation]

## Step 3: [Third Major Step]

[Detailed explanation]

## Common Issues & Troubleshooting

**Problem:** [Common issue]  
**Solution:** [How to fix it]

## Conclusion

[Summary of what was learned and next steps]

## Additional Resources

- [Link to related content]
- [Link to documentation]
MD;
    }

    private function getReviewTemplate(): string
    {
        return <<<'MD'
## Overview

[Brief introduction to what's being reviewed]

**Rating:** ⭐⭐⭐⭐☆ (4/5)

## Key Features

- Feature 1
- Feature 2
- Feature 3

## Pros

✅ Advantage 1  
✅ Advantage 2  
✅ Advantage 3

## Cons

❌ Disadvantage 1  
❌ Disadvantage 2

## Pricing

[Pricing details and value assessment]

## Who Is This For?

[Target audience and use cases]

## Final Verdict

[Overall assessment and recommendation]

**Would I recommend it?** [Yes/No/Maybe + explanation]
MD;
    }

    private function getCaseStudyTemplate(): string
    {
        return <<<'MD'
## Executive Summary

[Brief overview of the case study and key results]

## The Challenge

[Describe the problem or situation]

## The Solution

[Explain the approach taken]

## Implementation

### Phase 1: [Planning]
[Details]

### Phase 2: [Execution]
[Details]

### Phase 3: [Optimization]
[Details]

## Results

📊 **Key Metrics:**
- Metric 1: [Result]
- Metric 2: [Result]
- Metric 3: [Result]

## Lessons Learned

1. Lesson 1
2. Lesson 2
3. Lesson 3

## Takeaways

[Key actionable insights for readers]
MD;
    }

    private function getNewsTemplate(): string
    {
        return <<<'MD'
## What Happened

[Summary of the news/update]

## Key Details

- Important fact 1
- Important fact 2
- Important fact 3

## Why This Matters

[Analysis of significance and impact]

## What's Next

[Expected developments or actions]

## Our Take

[Brief editorial perspective]

## Stay Updated

[Call to action for following updates]
MD;
    }

    private function getListicleTemplate(): string
    {
        return <<<'MD'
## Introduction

[Why this list matters and what readers will gain]

## 1. [First Item]

[Description, features, why it made the list]

**Best for:** [Use case]

## 2. [Second Item]

[Description and analysis]

**Best for:** [Use case]

## 3. [Third Item]

[Description and analysis]

**Best for:** [Use case]

## 4. [Fourth Item]

[Continue pattern...]

## Comparison Table

| Item | Feature 1 | Feature 2 | Price | Rating |
|------|-----------|-----------|-------|--------|
| #1   | ✅        | ✅        | $$    | ⭐⭐⭐⭐⭐ |
| #2   | ✅        | ❌        | $     | ⭐⭐⭐⭐  |

## How to Choose

[Guidance on selecting the best option for reader's needs]

## Conclusion

[Summary and final recommendation]
MD;
    }

    private function getOpinionTemplate(): string
    {
        return <<<'MD'
## My Position

[Clear statement of your stance on the topic]

## The Context

[Background information readers need to understand the issue]

## Why I Believe This

### Argument 1

[Supporting evidence and reasoning]

### Argument 2

[Supporting evidence and reasoning]

### Argument 3

[Supporting evidence and reasoning]

## Counterarguments

[Acknowledge opposing views]

**But here's why I disagree:** [Rebuttal]

## Implications

[What this means for readers/industry/society]

## What Should We Do?

[Actionable recommendations or call to action]

## Final Thoughts

[Concluding perspective]
MD;
    }
}
