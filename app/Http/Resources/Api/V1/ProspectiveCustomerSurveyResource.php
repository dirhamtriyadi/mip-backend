<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProspectiveCustomerSurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_enum' => [
                'label' => $this->status ? $this->status->label() : null,
                'value' => $this->status ? $this->status->value : null,
            ],
            'name' => $this->name,
            'address' => $this->address,
            'number_ktp' => $this->number_ktp,
            'address_status' => $this->address_status,
            'phone_number' => $this->phone_number,
            'npwp' => $this->npwp,
            'company_name' => $this->company_name,
            'employee_tenure' => $this->employee_tenure,
            'job_level' => $this->job_level,
            'employee_status' => $this->employee_status,
            'salary' => $this->salary,
            'other_business' => $this->other_business,
            'monthly_living_expenses' => $this->monthly_living_expenses,
            'children' => $this->children,
            'wife' => $this->wife,
            'couple_jobs' => $this->couple_jobs,
            'couple_business' => $this->couple_business,
            'couple_income' => $this->couple_income,
            'bank_debt' => $this->bank_debt,
            'cooperative_debt' => $this->cooperative_debt,
            'personal_debt' => $this->personal_debt,
            'online_debt' => $this->online_debt,
            'customer_character_analysis' => $this->customer_character_analysis,
            'financial_report_analysis' => $this->financial_report_analysis,
            'slik_result' => $this->slik_result,
            'info_provider_name' => $this->info_provider_name,
            'info_provider_position' => $this->info_provider_position,
            'workplace_condition' => $this->workplace_condition,
            'employee_count' => $this->employee_count,
            'business_duration' => $this->business_duration,
            'office_address' => $this->office_address,
            'office_phone' => $this->office_phone,
            'loan_application' => $this->loan_application,
            'recommendation_from_vendors' => $this->recommendation_from_vendors,
            'recommendation_from_treasurer' => $this->recommendation_from_treasurer,
            'recommendation_from_other' => $this->recommendation_from_other,
            'recommendation_pt' => $this->recommendation_pt,
            'recommendation_pt_enum' => [
                'label' => $this->recommendation_pt->label(),
                'value' => $this->recommendation_pt->value,
            ],
            'descriptionSurvey' => $this->descriptionSurvey,
            'locationSurvey' => $this->locationSurvey,
            'dateSurvey' => $this->dateSurvey,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'locationString' => $this->locationString,
            'signature_officer' => $this->signature_officer ? asset('storage/' . $this->signature_officer) : null,
            'signature_customer' => $this->signature_customer ? asset('storage/' . $this->signature_customer) : null,
            'signature_couple' => $this->signature_couple ? asset('storage/' . $this->signature_couple) : null,
            'workplace_image1' => $this->workplace_image1 ? asset('storage/' . $this->workplace_image1) : null,
            'workplace_image2' => $this->workplace_image2 ? asset('storage/' . $this->workplace_image2) : null,
            'customer_image' => $this->customer_image ? asset('storage/' . $this->customer_image) : null,
            'ktp_image' => $this->ktp_image ? asset('storage/' . $this->ktp_image) : null,
            'loan_guarantee_image1' => $this->loan_guarantee_image1 ? asset('storage/' . $this->loan_guarantee_image1) : null,
            'loan_guarantee_image2' => $this->loan_guarantee_image2 ? asset('storage/' . $this->loan_guarantee_image2) : null,
            'kk_image' => $this->kk_image ? asset('storage/' . $this->kk_image) : null,
            'id_card_image' => $this->id_card_image ? asset('storage/' . $this->id_card_image) : null,
            'salary_slip_image1' => $this->salary_slip_image1 ? asset('storage/' . $this->salary_slip_image1) : null,
            'salary_slip_image2' => $this->salary_slip_image2 ? asset('storage/' . $this->salary_slip_image2) : null,
        ];
    }
}
