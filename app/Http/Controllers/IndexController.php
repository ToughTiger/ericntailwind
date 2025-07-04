<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class IndexController extends Controller
{
    public function contact(){
        $meta = [
            'title' => 'Contact Us | Eric Solutions Clinical Trial Services',
            'keywords' => 'Eric Solutions contact, clinical trial management support, EDC support, CTMS assistance, eTMF inquiries, clinical trial solutions contact',
            'description' => 'Connect with Eric Solutions for clinical trial management support in EDC, CTMS, eTMF, IWRS and IRT solutions tailored to your needs.'
        ];

        return view('contacts.contacts', compact('meta'));


    }
public function gdpr(){
    $meta = [
        'title' => 'GDPR Compliance | Eric Solutions Data Privacy',
        'keywords' => 'Eric Solutions GDPR compliance, data privacy clinical trials, GDPR clinical trial services, data protection Eric Solutions',
        'description' => 'Eric Solutions ensures GDPR compliance and data privacy for secure, transparent clinical trial management.'
    ];
        return view('legal/gdpr', compact('meta'));
}
    public function privacy(){
        $meta = [
            'title' => 'Privacy Policy | Eric Solutions Secure Data Management.',
            'keywords' => 'Eric Solutions privacy policy, data security clinical trials, clinical trial data privacy, information security policy Eric Solutions',
            'description' => 'Review our privacy policy for secure data management and transparency in clinical trial services.'
        ];
        return view('legal/privacy_policy', compact('meta'));
    }

    public function cookies(){
        $meta = [
            'title' => 'Cookie Policy | Eric Solutions GDPR Transparency',
            'keywords' => 'Eric Solutions cookie policy, GDPR cookies, data collection clinical trial website, cookie compliance Eric Solutions.',
            'description' => 'Eric Solutions uses cookies for a better experience, ensuring transparency and GDPR compliance in data use.'
        ];
        return view('legal/cookies',compact('meta'));
    }

    public function csr(){
        $title = '';
        $metaDescription = '';
        return view('aitech/csr');
    }
    public function protocol(){
        $title = '';
        $metaDescription = '';
        return view('aitech/protocol');
    }

    public function why_eric(){
        $meta = [
            'title' => 'Why Eric Solutions | Clinical Trial Excellence',
            'keywords' => 'Eric Solutions advantages, clinical trial expertise, EDC and CTMS solutions, clinical trial management benefits',
            'description' => 'Discover why Eric Solutions is your trusted partner for clinical trial management with our expertise in EDC, CTMS, and more.'
        ];
        return view('why_eric', compact('meta'));
    }
    // public function blog(){
    //     $posts = Post::orderBy('created_at', 'desc')
    //     ->limit(3)
    //     ->with(['categories', 'tags'])
    //     ->where('is_published', '=', 1)
    //     ->get();
    //     dd($posts);
    //  return view('index', ['posts' => $posts]);
    // }
}
