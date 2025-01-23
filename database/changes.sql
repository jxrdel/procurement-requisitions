ALTER TABLE requisitions
ADD release_type NVARCHAR(255),
    request_category NVARCHAR(255),
    date_sent_checkstaff DATE,
    date_received_ap DATE,
    date_sent_vc DATE
