SELECT 
    D.DefID,
    L.LocationName,
    S.SeverityName,
    DATE_FORMAT(D.DateCreated, "%d %b %Y"),
    T.Status,
    Y.SystemName,
    D.Description,
    D.SpecLoc,
    D.LastUpdated
FROM
    CDL D
LEFT JOIN 
    location L
ON 
    L.LocationID = D.Location
LEFT JOIN 
    Severity S
ON 
    D.Severity = S.SeverityID
LEFT JOIN 
    Status T
ON 
    D.Status = T.StatusID
LEFT JOIN 
    system Y
ON 
    D.SystemAffected = Y.SystemID