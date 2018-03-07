SELECT 
    OldID,
    Location,
    SpecLoc,
    Severity,
    Description,
    Spec,
    DATE_FORMAT(DateCreated, "%d %b %Y"),
    Status,
    IdentifiedBy,
    SystemAffected,
    GroupToResolve,
    ActionOwner,
    EvidenceType,
    EvidenceLink,
    DateClosed,
    LastUpdated,
    Updated_by,
    Comments
FROM
    CDL
Where DefID=