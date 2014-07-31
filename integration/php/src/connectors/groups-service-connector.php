<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function FindGroups($serviceUri, $query, $debug = FALSE)
{
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'Query' => $query,
            'METHOD' => 'FINDGROUPS');

    return PostToService($serviceUri, http_build_query($params), $debug);
}

function GetGroup($serviceUri, $groupUuid = NULL, $groupName = NULL, $debug = FALSE)
{
    if ($groupUuid != NULL && $groupName != NULL)
        throw new Exception("Cannot specify both group ID and name.");
    else if ($groupUuid == NULL && $groupName == NULL)
        throw new Exception("Either group ID or group name must be specified.");            
    
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,            
            'METHOD' => 'GETGROUP');
            
    if ($groupUuid != NULL)
        $params['GroupID'] =  $groupUuid;
    else
        $params['Name'] = $groupName;

    return PostToService($serviceUri, http_build_query($params), $debug);
}

function GetGroupMembers($serviceUri, $groupUuid, $debug = FALSE)
{
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'GroupID' => $groupUuid,
            'METHOD' => 'GETGROUPMEMBERS');

    return PostToService($serviceUri, http_build_query($params), $debug);
}

function GetUserMemberships($serviceUri, $userUuid, $groupUuid, $debug = FALSE)
{
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'AgentID' => $userUuid,
            'METHOD' => 'GETMEMBERSHIP');
            
    if ($groupUuid == "all")
        $params['ALL'] = '';
    else if ($groupUuid == "active")
        $params['GroupID'] = UUID_ZERO;
    else
        $params['GroupID'] = $groupUuid;

    return PostToService($serviceUri, http_build_query($params), $debug);
}

/*
 * Add or update an existing group.
 * 
 * @param string $serviceURI 
 *      Inventory service URI
 * @param string $groupName
 *      Name of the group.  Ignored if the group already exists.
 * @param UUID $founderID 
 *      UUID of the user account of the founder of the group.  Ignored if the group already exists.*  
 * @param string $charter
 *      Charter information (description) for the group.
 * @param UUID $insigniaID
 *      Texture asset to use as the picture for the group.  If UUID.Zero then no picture will be available.
 * @param boolean $allowPublish
 *      In Linden Lab probably governs whether this group is published to search.
 *      Very probably currently ignored in core OpenSimulator.
 * @param boolean $maturePublish
 *      In Linden Lab probably stops this group being shown to users in search unless they have Mature set in their preferences.
 *      Very probably currently ignored in core OpenSimulator.
 * @param int $membershipFee
 *      The amount of virtual currency users will have to pay to join this group.  In OpenSimulator, unless you have
 *      a currency module active you will normally want to set this to zero.
 * @param bool $shownInList
 *      Governs whether the group is shown to the user when they do a general search for groups.  Unfortunately, this
 *      also governs whether it shows up in the FindGroups call.  Currently, set to true whenever possible, otherwise
 *      the group information can only be retrieved via GetGroup.
 *   
 * @return SimpleXmlElement folder XML element 
 */
function AddGroup(
    $serviceUri, $groupName, $founderID, $charter, $groupPictureID, 
    $allowPublish, $maturePublish, $openEnrollment, $membershipFee, $shownInList = TRUE,
    $debug = FALSE)
{
    if (!is_bool($allowPublish))
        throw new InvalidArgumentException("allowPublish '$allowPublish' is not a bool");
    
    if (!IsUuid($founderID))
        throw new InvalidArgumentException("founderID '$founderID' is not a valid UUID");
    
    if (!IsUuid($groupPictureID))
        throw new InvalidArgumentException("groupPictureID '$groupPictureID' is not a valid UUID");
    
    if (!is_bool($openEnrollment))
        throw new InvalidArgumentException("openEnrollment '$openEnrollment' is not a bool");
    
    if (!is_int($membershipFee) || $membershipFee < 0)
        throw new InvalidArgumentException("membershipFee '$membershipFee' is not a positive or zero integer");
        
    if (!is_bool($shownInList))
        throw new InvalidArgumentException("shownInList '$shownInList' is not a bool");
    
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'GroupName' => $groupName,
            'AllowPublish' => $allowPublish ? "true" : "false",
            'MaturePublish' => $maturePublish ? "true" : "false",
            'OpenEnrollment' => $openEnrollment ? "true" : "false",
            'MembershipFee' => $membershipFee,
            'Charter' => $charter,
            'FounderID' => $founderID,
            'InsigniaID' => $groupPictureID,
            'ShownInList' => $shownInList ? "true" : "false",
            // We need this to get around a bug in OpenSimulator 0.8 and before where not specifying some ServiceLocation 
            // would set GroupName to "".  This is only used in Hypergrid so we can safely set this to a space.
            'ServiceLocation' => " ", 
            'METHOD' => 'PUTGROUP',
            'OP' => 'ADD');
            
    $responseXml = PostToService($serviceUri, http_build_query($params), $debug);    
}

?>