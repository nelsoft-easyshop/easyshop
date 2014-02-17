<?php



/**
 * MemberCategoryPreference utility class
 *
 * @author Czar Pino
 */
class MemberCategoryPreferenceUtility
{
    /**
     * Increase preference of member for specified category
     * 
     * @param int $memberId Id of member
     * @param int $categoryId Id of preferred category
     * @param int $val Amount to increment (Optional; default is 1)
     * 
     * @return MemberCategoryPreference on success, FALSE otherwise
     */
    public function upPreference($memberId, $categoryId, $val = 1)
    {
        $ci =& get_instance();
        $ci->load->model("MemberCategoryPreference");
        
        $memberCategoryPref = new MemberCategoryPreference();
        $memberCategoryPref = $memberCategoryPref->retrieveByMemberAndCategory($memberId, $categoryId);
        
        if (FALSE === $memberCategoryPref) {
            $memberCategoryPref = new MemberCategoryPreference();
            $memberCategoryPref->member_id = $memberId;
            $memberCategoryPref->cat_id = $categoryId;
            $memberCategoryPref->value = $val;
            
            if (FALSE !== $memberCategoryPref->insert()) {
                return $memberCategoryPref;
            }
        }
        else {
            $memberCategoryPref->value += $val;
            if (FALSE !== $memberCategoryPref->update()) {
                return $memberCategoryPref;
            }
        }
        
        return FALSE;
    }
}
