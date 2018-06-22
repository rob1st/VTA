SELECT 'location' AS element, COUNT(locationID) AS count FROM Location WHERE locationName <> ''
UNION
SELECT 'system', COUNT(systemID) FROM System WHERE system <> ''
UNION
SELECT 'component', COUNT(compID) FROM component WHERE compName <> ''
UNION
SELECT 'deficiency', COUNT(deftypeID) FROM defType WHERE deftypeName <> ''
UNION
SELECT 'contract', COUNT(contractID) FROM Contract WHERE contract <> ''
UNION
SELECT 'evidence', COUNT(eviTypeID) FROM EvidenceType WHERE eviType <> ''
UNION
SELECT 'status', COUNT(testStatID) FROM testStatus WHERE testStatName <> ''
UNION
SELECT 'severity', COUNT(severityID) FROM Severity WHERE severityName <> ''