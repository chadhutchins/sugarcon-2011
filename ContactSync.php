<?php

class ContactSync {
    
    function __construct()
    {
        // do something on construction
    }
    
    function sync(&$bean,$event,$arguments)
    {
        $table_name = "cr_registraion_contacts_c";
        $custom_module_link_id_name = "cr_registrc1f4tration_idb";
        $relate_module_link_id_name = "cr_registr81f4ontacts_ida";
        $email_field = "email";
        $email_address_to_contact = "chad.hutchins@milsoft.com";
        
        global $mod_strings, $sugar_config;
        // if it's a new record
        // attempt to sync with a contact
        if (empty($bean->fetched_row['id']) && !empty($bean->$email_field))
        {
            $query = "SELECT eabr.bean_id as contact_id 
                        FROM email_addr_bean_rel eabr 
                        INNER JOIN email_addresses ea ON ea.id=eabr.email_address_id 
                        WHERE ea.email_address LIKE '".$bean->$email_field."' 
                        AND eabr.bean_module='Contacts'";
            $results = $bean->db->query($query);
            $result = $bean->db->fetchByAssoc($results);
            if ($result)
            {
                $contact_id = $result["contact_id"];
                $bean->set_relationship($table_name, array($custom_module_link_id_name=>$bean->id, $relate_module_link_id_name=>$contact_id), false);
            }
            else
            {
                $subject = "An orphaned ".$mod_strings['LBL_MODULE_NAME']." record was created.";

                $url = $sugar_config["site_url"]."/index.php?module=".$bean->object_name."&action=DetailView&record=".$bean->id;;
                $body = "Deal with it... View the record here: ".$url;

                mail($email_address_to_contact,$subject,$body);
            }
        }
    }
    
}

?>