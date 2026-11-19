SET XACT_ABORT ON;
BEGIN TRANSACTION;

IF COL_LENGTH('dbo.JbtVessel','DeadweightTonnage') IS NULL
    ALTER TABLE dbo.JbtVessel ADD DeadweightTonnage decimal(14,2) NULL;
IF COL_LENGTH('dbo.JbtVessel','IMONumber') IS NULL
    ALTER TABLE dbo.JbtVessel ADD IMONumber varchar(7) NULL;
IF COL_LENGTH('dbo.JbtVessel','RouteName') IS NULL
    ALTER TABLE dbo.JbtVessel ADD RouteName nvarchar(200) NULL;
IF COL_LENGTH('dbo.JbtVessel','MainTankLiters') IS NULL
    ALTER TABLE dbo.JbtVessel ADD MainTankLiters decimal(14,2) NULL;
IF COL_LENGTH('dbo.JbtVessel','DailyTankLiters') IS NULL
    ALTER TABLE dbo.JbtVessel ADD DailyTankLiters decimal(14,2) NULL;
IF COL_LENGTH('dbo.PipelineFlowSettings','ShowCustomerQuota') IS NULL
    ALTER TABLE dbo.PipelineFlowSettings ADD ShowCustomerQuota bit NOT NULL CONSTRAINT DF_PFS_ShowQuota DEFAULT 1;
IF COL_LENGTH('dbo.PipelineFlowSettings','RequiredVesselFields') IS NULL
    ALTER TABLE dbo.PipelineFlowSettings ADD RequiredVesselFields varchar(200) NULL;

IF OBJECT_ID('dbo.PipelineCustomerManagement','U') IS NULL
    CREATE TABLE dbo.PipelineCustomerManagement (
        BusinessEntityID int PRIMARY KEY REFERENCES dbo.JbtBusinessEntity(BusinessEntityID),
        SpbbID int NOT NULL, Enabled bit NOT NULL DEFAULT 0
    );
IF OBJECT_ID('dbo.PipelineCustomerVesselAccess','U') IS NULL
    CREATE TABLE dbo.PipelineCustomerVesselAccess (
        UserID varchar(100) NOT NULL, VesselID int NOT NULL REFERENCES dbo.JbtVessel(VesselID),
        PRIMARY KEY(UserID,VesselID)
    );
IF OBJECT_ID('dbo.PipelineCustomerPasswordInvite','U') IS NULL
    CREATE TABLE dbo.PipelineCustomerPasswordInvite (
        UserID varchar(100) PRIMARY KEY, TokenHash char(64) NOT NULL UNIQUE, ExpiresAt datetime2 NOT NULL
    );
IF OBJECT_ID('dbo.PipelineFlowSource','U') IS NULL
CREATE TABLE dbo.PipelineFlowSource (
 RequestID int NOT NULL REFERENCES dbo.PipelineFlowRequest(RequestID),
 CaptureID int NOT NULL UNIQUE REFERENCES dbo.PipelineSppCapture(CaptureID),
 SourceKey nvarchar(200) NOT NULL, VerifiedBy varchar(100) NOT NULL,
 VerifiedAt datetime2 NOT NULL DEFAULT SYSDATETIME(),
 PRIMARY KEY(RequestID,CaptureID)
);
COMMIT TRANSACTION;
