<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function edc(){
        $meta = [
            'title' => 'Electronic Data Capture Solutions | Eric Solutions',
            'keywords' => 'electronic data capture, clinical trials, data collection, technology solutions',
            'description' => 'Enhance clinical trials with our advanced Electronic Data Capture technology, ensuring accurate and efficient data collection'
        ];
        return view('technology/edc', compact('meta'));
    }
    public function ctms(){
        $meta = [
            'title' => 'Clinical Trial Management Software | Eric Solutions',
            'keywords' => 'clinical trial management software, trial optimization, regulatory compliance, software solutions',
            'description' => ' Optimize your clinical trials with our comprehensive management software designed for efficiency and regulatory compliance'
        ];
        return view('technology/ctms', compact('meta'));
    }
    public function etmf_tech(){
        $meta = [
            'title' => 'Electronic Trial Master File (eTMF) | Eric Solutions',
            'keywords' => 'electronic trial master file, eTMF, compliance, audit history',
            'description' => 'Ensure real-time compliance and audit history with our advanced Electronic Trial Master File solutions.'
        ];
        return view('technology/etmf', compact('meta'));
    }

    public function irt(){
        $meta = [
            'title' => 'Interactive Response Technology | Eric Solutions',
            'keywords' => 'interactive response technology, patient engagement, clinical trials, technology solutions',
            'description' => 'Streamline clinical trial operations with our innovative Interactive Response Technology for better patient engagement'
        ];
        return view('technology/irt', compact('meta'));
    }

}
