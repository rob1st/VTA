# show CDL reqBy IDs and their associated ReqBy descriptions (and see where CDL reqBy IDs are wrong, no assoc descrip)
"select c.requiredBy, r.requiredBy from CDL c left join RequiredBy r on c.requiredBy=r.reqByID group by c.requiredBy;"

# show Defs matching a particular reqBy and Status
# .eg., "select count(defID) from CDL join Status on CDL.status=Status.statusID where requiredBy=60 && Status.status='Open';"
"select count(defID) from CDL join Status on CDL.status=Status.statusID where requiredBy=? && Status.status=?;"