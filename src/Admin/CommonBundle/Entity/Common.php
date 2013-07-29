<?php

namespace Admin\CommonBundle\Entity;


/**
 * User
 */
class Common
{
    public static $per_page = 2;

    public static function setPerPage($per_page)
    {
      self::$per_page = $per_page;
    }

    public static function bindQueryParams($currentObj, $sortby = 'id', $searchBy = '')
    {

        // Set default values
        $amExtraParameters = array();
        $amExtraParameters['snPaging']        = $currentObj->getRequest()->request->get('paging', self::$per_page);
        $amExtraParameters['snPage']          = $currentObj->getRequest()->request->get('page', 1);
        $amExtraParameters['ssSearchValue']   = trim($currentObj->getRequest()->request->get('searchvalue', ''));
        $amExtraParameters['ssSelectStatus']  = $currentObj->getRequest()->request->get('selectstatus', '');
        $amExtraParameters['ssSortBy']        = $currentObj->getRequest()->request->get('sortby', $sortby);
        $amExtraParameters['ssSortMode']      = $currentObj->getRequest()->request->get('sortmode', 'asc');

        $amExtraParameters['ssSearchBy']      = $currentObj->getRequest()->request->get('searchby') == "" ? $searchBy : "";

        $ssSortQuerystr = $ssQuerystr = 'page='.$currentObj->getRequest()->request->get('page',1).'&paging='.$currentObj->getRequest()->request->get('paging', self::$per_page);

        if($amExtraParameters['ssSearchValue'] != '' )        // Search parameters
        {
            $ssQuerystr    .= '&searchvalue='.$amExtraParameters['ssSearchValue'].'&searchby='.$amExtraParameters['ssSearchBy'];
            $ssSortQuerystr.= '&searchvalue='.$amExtraParameters['ssSearchValue'].'&searchby='.$amExtraParameters['ssSearchBy'];
        }

        if($amExtraParameters['ssSelectStatus'] != '' )        // Status selection
        {
            $ssQuerystr    .= '&selectstatus='.$amExtraParameters['ssSelectStatus'];
            $ssSortQuerystr.= '&selectstatus='.$amExtraParameters['ssSelectStatus'];
        }
        // Sorting parameters
        if($amExtraParameters['ssSortBy'] != '' )
            $ssQuerystr .= '&sortby='.$amExtraParameters['ssSortBy'].'&sortmode='.$amExtraParameters['ssSortMode'];

        $amExtraParameters['ssQuerystr']     = $ssQuerystr;
        $amExtraParameters['ssSortQuerystr'] = $ssSortQuerystr;
        return $amExtraParameters;
    }

    /**
    * Function use to set setCriteria
    *
    * @param   object   $omCriteria     query-object
    * @param   array    $amExtraParameters exra-prameters array (search, sort)
    * @param   string   $ssStatusCondition  status (active/inactive/all)
    *
    * @return  object
    */
    public static function setCriteria($omCriteria, $alias, $amExtraParameters = array(), $ssStatusCondition = '')
    {

          ($ssStatusCondition)    ? $omCriteria->andWhere($ssStatusCondition) : '';

        if(count($amExtraParameters) > 0)
        {
            if(
                (
                    (isset($amExtraParameters['ssSearchBy']) && is_numeric($amExtraParameters['ssSearchBy'])) ||
                    (isset($amExtraParameters['ssSearchBy']) && is_numeric($amExtraParameters['ssSortBy']))
                ) ||
                (
                    (isset($amExtraParameters['ssSortMode']) && !empty($amExtraParameters['ssSortMode'])) &&
                    (
                        (strtolower($amExtraParameters['ssSortMode']) != 'asc') &&
                        (strtolower($amExtraParameters['ssSortMode']) != 'desc')
                    )
                )
            )   return false;

            // Prepare search query
            if(
                (isset($amExtraParameters['ssSearchValue']) && $amExtraParameters['ssSearchValue'] != '') &&
                (isset($amExtraParameters['ssSearchBy']) && $amExtraParameters['ssSearchBy'] != '')
            )
            {
                $ssSearchQuery  = $alias.'.'.$amExtraParameters['ssSearchBy']." LIKE '%".addslashes($amExtraParameters['ssSearchValue'])."%' ";
                $omCriteria->andWhere($ssSearchQuery);
            }

            // Prepare sort query
            if(
                (isset($amExtraParameters['ssSortBy']) && $amExtraParameters['ssSortBy'] != '') &&
                (isset($amExtraParameters['ssSortMode']) && $amExtraParameters['ssSortMode'] != '')
            )
            {
                $ssSortQuery    = $amExtraParameters['ssSortBy'].' '.$amExtraParameters['ssSortMode'];
                $omCriteria->addOrderBy($alias.'.'.$amExtraParameters['ssSortBy'],$amExtraParameters['ssSortMode']);
            }
        }
        return $omCriteria;
    }

    public function encodePassword($password, $salt)
    {
        return sha1($salt.$password); // Custom function for encrypt
    }

    public function isPasswordValid($encoded, $password, $salt)
    {
        return $encoded === $this->encodePassword($password, $salt);
    }

    public function generateSalt($email)
    {
        return md5($email.gettimeofday(true));
    }
}