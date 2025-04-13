<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProspectiveCustomerSurvey extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'prospective-customer-surveys';

    protected $fillable = [
        'prospective_customer_id',
        'user_id',
        'status',
        'name',
        'address',
        'number_ktp',
        'address_status',
        'phone_number',
        'npwp',
        'job_type',
        'company_name',
        'job_level',
        'employee_tenure',
        'employee_status',
        'salary',
        'other_business',
        'monthly_living_expenses',
        'children',
        'wife',
        'couple_jobs',
        'couple_business',
        'couple_income',
        'bank_debt',
        'cooperative_debt',
        'personal_debt',
        'online_debt',
        'customer_character_analysis',
        'financial_report_analysis',
        'slik_result',
        'info_provider_name',
        'info_provider_position',
        'workplace_condition',
        'employee_count',
        'business_duration',
        'office_address',
        'office_phone',
        'loan_application',
        'recommendation_from_vendors',
        'recommendation_from_treasurer',
        'recommendation_from_other',
        'source_1_full_name',
        'source_1_gender',
        'source_1_source_relationship',
        'source_1_source_character',
        'source_1_knows_prospect_customer',
        'source_1_prospect_lives_at_address',
        'source_1_length_of_residence',
        'source_1_house_ownership_status',
        'source_1_prospect_status',
        'source_1_number_of_dependents',
        'source_1_prospect_character',
        'source_2_full_name',
        'source_2_gender',
        'source_2_source_relationship',
        'source_2_source_character',
        'source_2_knows_prospect_customer',
        'source_2_prospect_lives_at_address',
        'source_2_length_of_residence',
        'source_2_house_ownership_status',
        'source_2_prospect_status',
        'source_2_number_of_dependents',
        'source_2_prospect_character',
        'recommendation_pt',
        'descriptionSurvey',
        'locationSurvey',
        'dateSurvey',
        'latitude',
        'longitude',
        'locationString',
        'signature_officer',
        'signature_customer',
        'signature_couple',
        'workplace_image1',
        'workplace_image2',
        'customer_image',
        'ktp_image',
        'loan_guarantee_image1',
        'loan_guarantee_image2',
        'kk_image',
        'id_card_image',
        'salary_slip_image1',
        'salary_slip_image2',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prospectiveCustomer()
    {
        return $this->belongsTo(ProspectiveCustomer::class);
    }
}
