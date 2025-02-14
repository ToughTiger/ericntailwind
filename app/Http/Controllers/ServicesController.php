<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function clinical_operation()
    {
        $meta = [
            'title' => 'Clinical Operations & Project Management | Eric Solutions',
            'keywords' => 'clinical operations, project management, regulatory compliance, clinical development',
            'description' => 'Optimize clinical development with our expert project management services, ensuring regulatory compliance and efficiency'
        ];
        return view('services/clinical_operation', compact('meta'));

    }

    public function biostatistics()
    {
        $meta = [
            'title' => 'Biostatistics Services | Eric Solutions',
            'keywords' => 'biostatistics, clinical trials, data analysis, adaptive designs',
            'description' => 'Expert biostatistics services for clinical trials, including adaptive designs and interim analysis to enhance data reliability'
        ];
        return view('services/biostatistics', compact('meta'));

    }

    public function clinical_data()
    {
        $meta = [
            'title' => 'Clinical Data Standards | Eric Solutions',
            'keywords' => 'clinical data standards, data management, regulatory compliance, clinical trials',
            'description' => 'Implement effective clinical data standards for smooth data management and compliance in clinical trials'
        ];
        return view('services/clinical_data', compact('meta'));

    }

    public function data_management()
    {
        $meta = [
            'title' => 'Data Management Services | Eric Solutions',
            'keywords' => 'data management, clinical trials, high-quality data, data analysis',
            'description' => 'Deliver high-quality outputs with industry-recognized data management systems tailored to your clinical trials'
        ];
        return view('services/data_management', compact('meta'));

    }

    public function medical_writing()
    {
        $meta = [
            'title' => 'Medical Writing Services | Eric Solutions',
            'keywords' => 'medical writing, clinical study reports, accuracy, regulatory submissions',
            'description' => 'Professional medical writing for clinical study reports, ensuring accuracy and timely delivery of results'
        ];
        return view('services/medical_writing', compact('meta'));
    }

    public function pharmacovigilance()
    {
        $meta = [
            'title' => 'Pharmacovigilance Solutions | Eric Solutions',
            'keywords' => 'pharmacovigilance, drug safety, regulatory compliance, safety monitoring',
            'description' => 'Comprehensive pharmacovigilance services ensuring drug safety and compliance with regulatory requirements'
        ];
        return view('services/pharmacovigilance', compact('meta'));

    }

    public function etmf()
    {
        $meta = [
            'title' => 'eTMF Services | Eric Solutions',
            'keywords' => 'eTMF services, clinical documentation, compliance, data integrity',
            'description' => 'Streamline clinical documentation with our user-friendly eTMF services, ensuring compliance and data integrity'
        ];
        return view('services/etmf_services', compact('meta'));

    }

}
