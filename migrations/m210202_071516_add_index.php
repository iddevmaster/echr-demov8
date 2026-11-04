<?php

use yii\db\Migration;

/**
 * Class m210202_071516_add_index
 */
class m210202_071516_add_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_agenda_deleted', 'agenda', 'deleted');
        $this->createIndex('idx_agenda_sort', 'agenda', 'sort');
        $this->createIndex('idx_agenda_addable', 'agenda', 'addable');
        $this->createIndex('idx_agenda_is_submission', 'agenda', 'is_submission');
        $this->createIndex('idx_agenda_need_resolution', 'agenda', 'need_resolution');

        $this->createIndex('idx_agenda_auto_desc_deleted', 'agenda_auto_desc', 'deleted');
        $this->createIndex('idx_agenda_auto_desc_auto_type', 'agenda_auto_desc', 'auto_type');

        $this->createIndex('idx_agenda_result_document_deleted', 'agenda_result_document', 'deleted');

        $this->createIndex('idx_agenda_submission_type_deleted', 'agenda_submission_type', 'deleted');

        $this->createIndex('idx_alert_type', 'alert', 'type');
        $this->createIndex('idx_alert_is_acknowledged', 'alert', 'is_acknowledged');

        $this->createIndex('idx_alert_peroid_deleted', 'alert_peroid', 'deleted');
        $this->createIndex('idx_alert_peroid_active', 'alert_peroid', 'active');

        $this->createIndex('idx_committee_position_deleted', 'committee_position', 'deleted');

        $this->createIndex('idx_continue_assess_form_deleted', 'continue_assess_form', 'deleted');

        $this->createIndex('idx_continue_assess_form_ethics_deleted', 'continue_assess_form_ethics', 'deleted');
        $this->createIndex('idx_continue_assess_form_ethics_is_appropriate', 'continue_assess_form_ethics', 'is_appropriate');

        $this->createIndex('idx_continue_assess_form_review_deleted', 'continue_assess_form_review', 'deleted');

        $this->createIndex('idx_c_assess_form_deleted', 'c_assess_form', 'deleted');

        $this->createIndex('idx_department_deleted', 'department', 'deleted');

        $this->createIndex('idx_deviation_assess_form_deleted', 'deviation_assess_form', 'deleted');

        $this->createIndex('idx_deviation_assess_form_review_deleted', 'deviation_assess_form_review', 'deleted');

        $this->createIndex('idx_deviation_event_deleted', 'deviation_event', 'deleted');
        $this->createIndex('idx_deviation_event_is_major_minor_com', 'deviation_event', 'is_major_minor_com');

        $this->createIndex('idx_deviation_event_ethics_deleted', 'deviation_event_ethics', 'deleted');

        $this->createIndex('idx_deviation_event_type_deleted', 'deviation_event_type', 'deleted');

        $this->createIndex('idx_deviation_type_deleted', 'deviation_type', 'deleted');

        $this->createIndex('idx_division_deleted', 'division', 'deleted');

        $this->createIndex('idx_document_deleted', 'document', 'deleted');

        $this->createIndex('idx_document_submission_type_deleted', 'document_submission_type', 'deleted');
        $this->createIndex('idx_document_submission_type_is_require', 'document_submission_type', 'is_require');
        $this->createIndex('idx_document_submission_type_sort', 'document_submission_type', 'sort');
        $this->createIndex('idx_document_submission_type_is_event', 'document_submission_type', 'is_event');

        $this->createIndex('idx_email_queue_deleted', 'email_queue', 'deleted');

        $this->createIndex('idx_ethics_deleted', 'ethics', 'deleted');
        
        $this->createIndex('idx_funding_source_deleted', 'funding_source', 'deleted');

        $this->createIndex('idx_job_category_deleted', 'job_category', 'deleted');

        $this->createIndex('idx_meeting_deleted', 'meeting', 'deleted');
        $this->createIndex('idx_meeting_start_date', 'meeting', 'start_date');
        $this->createIndex('idx_meeting_end_date', 'meeting', 'end_date');
        $this->createIndex('idx_meeting_status', 'meeting', 'status');
        $this->createIndex('idx_meeting_is_public', 'meeting', 'is_public');
        $this->createIndex('idx_meeting_year', 'meeting', 'year');
        $this->createIndex('idx_meeting_checked_status', 'meeting', 'checked_status');
        $this->createIndex('idx_meeting_meeting_no', 'meeting', 'meeting_no');

        $this->createIndex('idx_meeting_agenda_deleted', 'meeting_agenda', 'deleted');
        $this->createIndex('idx_meeting_agenda_parent_id', 'meeting_agenda', 'parent_id');
        $this->createIndex('idx_meeting_agenda_sort', 'meeting_agenda', 'sort');
        $this->createIndex('idx_meeting_agenda_addable', 'meeting_agenda', 'addable');
        $this->createIndex('idx_meeting_agenda_sortable', 'meeting_agenda', 'sortable');
        $this->createIndex('idx_meeting_agenda_need_resolution', 'meeting_agenda', 'need_resolution');

        $this->createIndex('idx_meeting_person_deleted', 'meeting_person', 'deleted');

        $this->createIndex('idx_meeting_room_deleted', 'meeting_room', 'deleted');

        $this->createIndex('idx_organization_deleted', 'organization', 'deleted');
        $this->createIndex('idx_organization_is_internal', 'organization', 'is_internal');

        $this->createIndex('idx_panel_deleted', 'panel', 'deleted');

        $this->createIndex('idx_person_deleted', 'person', 'deleted');
        $this->createIndex('idx_person_is_paediatrician', 'person', 'is_paediatrician');
        $this->createIndex('idx_person_gender', 'person', 'gender');
        $this->createIndex('idx_person_is_external', 'person', 'is_external');
        $this->createIndex('idx_person_reg_code', 'person', 'reg_code');
        $this->createIndex('idx_person_expertise', 'person', 'expertise');

        $this->createIndex('idx_person_role_deleted', 'person_role', 'deleted');
        $this->createIndex('idx_person_role_sign', 'person_role', 'sign');
        $this->createIndex('idx_person_role_status', 'person_role', 'status');

        $this->createIndex('idx_person_role_panel_deleted', 'person_role_panel', 'deleted');
        $this->createIndex('idx_person_role_panel_is_regular', 'person_role_panel', 'is_regular');

        $this->createIndex('idx_person_training_deleted', 'person_training', 'deleted');

        $this->createIndex('idx_position_deleted', 'position', 'deleted');

        $this->createIndex('idx_project_deleted', 'project', 'deleted');
        $this->createIndex('idx_project_start_date', 'project', 'start_date');
        $this->createIndex('idx_project_end_date', 'project', 'end_date');
        $this->createIndex('idx_project_is_child_project', 'project', 'is_child_project');
        $this->createIndex('idx_project_status', 'project', 'status');
        $this->createIndex('idx_project_certified_date', 'project', 'certified_date');
        $this->createIndex('idx_project_project_code', 'project', 'project_code');
        $this->createIndex('idx_project_certificate_no', 'project', 'certificate_no');
        $this->createIndex('idx_project_expire_at', 'project', 'expire_at');
        $this->createIndex('idx_project_is_closed', 'project', 'is_closed');
        $this->createIndex('idx_project_crec_number', 'project', 'crec_number');
        $this->createIndex('idx_project_crec_number_certificate', 'project', 'crec_number_certificate');
        $this->createIndex('idx_project_next_progress_at', 'project', 'next_progress_at');

        $this->createIndex('idx_project_agenda_answer_deleted', 'project_agenda_answer', 'deleted');

        $this->createIndex('idx_project_agenda_question_deleted', 'project_agenda_question', 'deleted');

        $this->createIndex('idx_project_code_history_old_code', 'project_code_history', 'old_code');
        $this->createIndex('idx_project_code_history_new_code', 'project_code_history', 'new_code');

        $this->createIndex('idx_project_consultant_mail_sent', 'project_consultant', 'mail_sent');
        $this->createIndex('idx_project_consultant_acknowledge_status', 'project_consultant', 'acknowledge_status');
        $this->createIndex('idx_project_consultant_deleted', 'project_consultant', 'deleted');
        $this->createIndex('idx_project_consultant_ack_token', 'project_consultant', 'ack_token');
        
        $this->createIndex('idx_project_question_deleted', 'project_question', 'deleted');
        
        $this->createIndex('idx_project_question_choice_deleted', 'project_question_choice', 'deleted');
        
        $this->createIndex('idx_project_researcher_deleted', 'project_researcher', 'deleted');
        $this->createIndex('idx_project_researcher_mail_sent', 'project_researcher', 'mail_sent');
        $this->createIndex('idx_project_researcher_acknowledge_status', 'project_researcher', 'acknowledge_status');
        $this->createIndex('idx_project_researcher_ack_token', 'project_researcher', 'ack_token');

        $this->createIndex('idx_project_type_deleted', 'project_type', 'deleted');

        $this->createIndex('idx_questionnaire_answer_deleted', 'questionnaire_answer', 'deleted');

        $this->createIndex('idx_questionnaire_choice_deleted', 'questionnaire_choice', 'deleted');

        $this->createIndex('idx_questionnaire_title_deleted', 'questionnaire_title', 'deleted');
        $this->createIndex('idx_questionnaire_title_questionnaire_type', 'questionnaire_title', 'questionnaire_type');
        $this->createIndex('idx_questionnaire_title_order', 'questionnaire_title', 'order');

        $this->createIndex('idx_register_transaction_deleted', 'register_transaction', 'deleted');

        $this->createIndex('idx_resolution_deleted', 'resolution', 'deleted');

        $this->createIndex('idx_result_document_deleted', 'result_document', 'deleted');
        $this->createIndex('idx_result_document_resolution', 'result_document', 'resolution');
        $this->createIndex('idx_result_document_committee_resolution', 'result_document', 'committee_resolution');

        $this->createIndex('idx_review_choice_deleted', 'review_choice', 'deleted');

        $this->createIndex('idx_risk_deleted', 'risk', 'deleted');

        $this->createIndex('idx_role_deleted', 'role', 'deleted');

        $this->createIndex('idx_sae_assess_form_deleted', 'sae_assess_form', 'deleted');

        $this->createIndex('idx_sae_volunteer_deleted', 'sae_volunteer', 'deleted');
        $this->createIndex('idx_sae_volunteer_dead', 'sae_volunteer', 'dead');
        $this->createIndex('idx_sae_volunteer_cured', 'sae_volunteer', 'cured');
        $this->createIndex('idx_sae_volunteer_drug', 'sae_volunteer', 'drug');

        $this->createIndex('idx_sae_volunteer_ethics_deleted', 'sae_volunteer_ethics', 'deleted');

        $this->createIndex('idx_setting_deleted', 'setting', 'deleted');

        $this->createIndex('idx_submission_deleted', 'submission', 'deleted');
        $this->createIndex('idx_submission_status', 'submission', 'status');
        $this->createIndex('idx_submission_certified_date', 'submission', 'certified_date');
        $this->createIndex('idx_submission_is_meeting', 'submission', 'is_meeting');
        $this->createIndex('idx_submission_is_legacy', 'submission', 'is_legacy');
        $this->createIndex('idx_submission_is_accept', 'submission', 'is_accept');

        $this->createIndex('idx_submission_coi_person_deleted', 'submission_coi_person', 'deleted');

        $this->createIndex('idx_submission_committee_deleted', 'submission_committee', 'deleted');
        $this->createIndex('idx_submission_committee_status', 'submission_committee', 'status');
        $this->createIndex('idx_submission_committee_can_meeting', 'submission_committee', 'can_meeting');
        $this->createIndex('idx_submission_committee_is_meeting', 'submission_committee', 'is_meeting');
        $this->createIndex('idx_submission_committee_resolution', 'submission_committee', 'resolution');

        $this->createIndex('idx_submission_committee_document_deleted', 'submission_committee_document', 'deleted');

        $this->createIndex('idx_submission_committee_revise_deleted', 'submission_committee_revise', 'deleted');
        $this->createIndex('idx_submission_committee_revise_resolution', 'submission_committee_revise', 'resolution');
        $this->createIndex('idx_submission_committee_revise_is_meeting', 'submission_committee_revise', 'is_meeting');

        $this->createIndex('idx_submission_document_deleted', 'submission_document', 'deleted');
        $this->createIndex('idx_submission_document_status', 'submission_document', 'status');

        $this->createIndex('idx_submission_event_deleted', 'submission_event', 'deleted');
        $this->createIndex('idx_submission_event_code', 'submission_event', 'code');

        $this->createIndex('idx_submission_project_consultant_deleted', 'submission_project_consultant', 'deleted');
        $this->createIndex('idx_submission_project_consultant_status', 'submission_project_consultant', 'status');

        $this->createIndex('idx_submission_project_researcher_deleted', 'submission_project_researcher', 'deleted');
        $this->createIndex('idx_submission_project_researcher_status', 'submission_project_researcher', 'status');

        $this->createIndex('idx_submission_result_document_deleted', 'submission_result_document', 'deleted');

        $this->createIndex('idx_submission_status_history_status', 'submission_status_history', 'status');

        $this->createIndex('idx_submission_type_deleted', 'submission_type', 'deleted');
        $this->createIndex('idx_submission_type_is_new', 'submission_type', 'is_new');
        $this->createIndex('idx_submission_type_is_fullboard', 'submission_type', 'is_fullboard');
        $this->createIndex('idx_submission_type_is_exemption', 'submission_type', 'is_exemption');
        $this->createIndex('idx_submission_type_internal', 'submission_type', 'internal');
        $this->createIndex('idx_submission_type_meeting_consideration', 'submission_type', 'meeting_consideration');
        $this->createIndex('idx_submission_type_risk_assessment', 'submission_type', 'risk_assessment');
        $this->createIndex('idx_submission_type_progress', 'submission_type', 'progress');
        $this->createIndex('idx_submission_type_certify', 'submission_type', 'certify');
        $this->createIndex('idx_submission_type_add_subject', 'submission_type', 'add_subject');
        $this->createIndex('idx_submission_type_close', 'submission_type', 'close');

        $this->createIndex('idx_submission_type_assess_form_deleted', 'submission_type_assess_form', 'deleted');

        $this->createIndex('idx_submission_type_durations_deleted', 'submission_type_duration', 'deleted');

        $this->createIndex('idx_submission_type_group_deleted', 'submission_type_group', 'deleted');

        $this->createIndex('idx_submission_type_volunteer_number_deleted', 'submission_type_volunteer_number', 'deleted');

        $this->createIndex('idx_submission_volunteer_deleted', 'submission_volunteer', 'deleted');

        $this->createIndex('idx_submission_volunteer_number_deleted', 'submission_volunteer_number', 'deleted');

        $this->createIndex('idx_title_deleted', 'title', 'deleted');

        $this->createIndex('idx_user_auth_key', 'user', 'auth_key');
        $this->createIndex('idx_user_password_hash', 'user', 'password_hash');
        $this->createIndex('idx_user_password_reset_token', 'user', 'password_reset_token');
        $this->createIndex('idx_user_status', 'user', 'status');
        $this->createIndex('idx_user_verify_token', 'user', 'verify_token');

        $this->createIndex('idx_volunteer_deleted', 'volunteer', 'deleted');

        $this->createIndex('idx_volunteer_number_deleted', 'volunteer_number', 'deleted');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210202_071516_add_index cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210202_071516_add_index cannot be reverted.\n";

        return false;
    }
    */
}
