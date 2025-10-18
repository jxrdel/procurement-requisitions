<?php

namespace App;

enum RequestFormStatus
{
    public const CREATED = 'Created';
    public const APPROVED_BY_HOD = 'Approved By HOD';
    public const DENIED_BY_HOD = 'Denied By HOD';
    public const SENT_TO_HOD = 'Sent to HOD';
    public const SENT_TO_PS = 'Sent to PS';
    public const APPROVED_BY_PS = 'Approved by PS';
    public const DENIED_BY_PS = 'Denied by PS';
    public const SENT_TO_DPS = 'Sent to DPS';
    public const APPROVED_BY_DPS = 'Approved by DPS';
    public const DENIED_BY_DPS = 'Denied by DPS';
    public const SENT_TO_CMO = 'Sent to CMO';
    public const APPROVED_BY_CMO = 'Approved by CMO';
    public const DENIED_BY_CMO = 'Denied by CMO';
    public const SENT_TO_PROCUREMENT = 'Sent to Procurement';
    public const APPROVED_BY_PROCUREMENT = 'Approved by Procurement';
    public const DENIED_BY_PROCUREMENT = 'Denied by Procurement';
    public const COMPLETED = 'Completed';
}
