
-- --------------------------------------------------
-- Entity Designer DDL Script for SQL Server 2005, 2008, 2012 and Azure
-- --------------------------------------------------
-- Date Created: 06/10/2016 23:47:12
-- Generated from EDMX file: C:\Users\mattkgross\Documents\GitHub\WorkoutLog\WorkoutLog\Models\DataModels\Master.edmx
-- --------------------------------------------------

SET QUOTED_IDENTIFIER OFF;
GO
USE [WorkoutLog];
GO
IF SCHEMA_ID(N'dbo') IS NULL EXECUTE(N'CREATE SCHEMA [dbo]');
GO

-- --------------------------------------------------
-- Dropping existing FOREIGN KEY constraints
-- --------------------------------------------------

IF OBJECT_ID(N'[dbo].[FK_PlayerTeam_Player]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[PlayerTeam] DROP CONSTRAINT [FK_PlayerTeam_Player];
GO
IF OBJECT_ID(N'[dbo].[FK_PlayerTeam_Team]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[PlayerTeam] DROP CONSTRAINT [FK_PlayerTeam_Team];
GO
IF OBJECT_ID(N'[dbo].[FK_PlayerTeamPermissionsPlayer]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[PlayerTeamPermissions] DROP CONSTRAINT [FK_PlayerTeamPermissionsPlayer];
GO
IF OBJECT_ID(N'[dbo].[FK_PlayerTeamPermissionsTeam]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[PlayerTeamPermissions] DROP CONSTRAINT [FK_PlayerTeamPermissionsTeam];
GO
IF OBJECT_ID(N'[dbo].[FK_NewsPostPlayer]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[NewsPosts] DROP CONSTRAINT [FK_NewsPostPlayer];
GO
IF OBJECT_ID(N'[dbo].[FK_NewsPostTeam_NewsPost]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[NewsPostTeam] DROP CONSTRAINT [FK_NewsPostTeam_NewsPost];
GO
IF OBJECT_ID(N'[dbo].[FK_NewsPostTeam_Team]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[NewsPostTeam] DROP CONSTRAINT [FK_NewsPostTeam_Team];
GO
IF OBJECT_ID(N'[dbo].[FK_PlayerWorkoutPost]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[WorkoutPosts] DROP CONSTRAINT [FK_PlayerWorkoutPost];
GO
IF OBJECT_ID(N'[dbo].[FK_TeamWorkoutPost_Team]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[TeamWorkoutPost] DROP CONSTRAINT [FK_TeamWorkoutPost_Team];
GO
IF OBJECT_ID(N'[dbo].[FK_TeamWorkoutPost_WorkoutPost]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[TeamWorkoutPost] DROP CONSTRAINT [FK_TeamWorkoutPost_WorkoutPost];
GO

-- --------------------------------------------------
-- Dropping existing tables
-- --------------------------------------------------

IF OBJECT_ID(N'[dbo].[Teams]', 'U') IS NOT NULL
    DROP TABLE [dbo].[Teams];
GO
IF OBJECT_ID(N'[dbo].[Players]', 'U') IS NOT NULL
    DROP TABLE [dbo].[Players];
GO
IF OBJECT_ID(N'[dbo].[PlayerTeamPermissions]', 'U') IS NOT NULL
    DROP TABLE [dbo].[PlayerTeamPermissions];
GO
IF OBJECT_ID(N'[dbo].[NewsPosts]', 'U') IS NOT NULL
    DROP TABLE [dbo].[NewsPosts];
GO
IF OBJECT_ID(N'[dbo].[WorkoutPosts]', 'U') IS NOT NULL
    DROP TABLE [dbo].[WorkoutPosts];
GO
IF OBJECT_ID(N'[dbo].[PlayerTeam]', 'U') IS NOT NULL
    DROP TABLE [dbo].[PlayerTeam];
GO
IF OBJECT_ID(N'[dbo].[NewsPostTeam]', 'U') IS NOT NULL
    DROP TABLE [dbo].[NewsPostTeam];
GO
IF OBJECT_ID(N'[dbo].[TeamWorkoutPost]', 'U') IS NOT NULL
    DROP TABLE [dbo].[TeamWorkoutPost];
GO

-- --------------------------------------------------
-- Creating all tables
-- --------------------------------------------------

-- Creating table 'Teams'
CREATE TABLE [dbo].[Teams] (
    [Id] int IDENTITY(1,1) NOT NULL,
    [Name] nvarchar(max)  NOT NULL,
    [DateCreated] datetime  NOT NULL,
    [EnrollmentKey] nvarchar(max)  NOT NULL
);
GO

-- Creating table 'Players'
CREATE TABLE [dbo].[Players] (
    [Id] int IDENTITY(1,1) NOT NULL,
    [FirstName] nvarchar(max)  NOT NULL,
    [LastName] nvarchar(max)  NOT NULL,
    [UserId] nvarchar(max)  NOT NULL,
    [DateCreated] datetime  NOT NULL
);
GO

-- Creating table 'PlayerTeamPermissions'
CREATE TABLE [dbo].[PlayerTeamPermissions] (
    [Id] int IDENTITY(1,1) NOT NULL,
    [WorkoutPost] int  NOT NULL,
    [NewsPost] int  NOT NULL,
    [ManageMembers] int  NOT NULL,
    [Players_Id] int  NOT NULL,
    [Teams_Id] int  NOT NULL
);
GO

-- Creating table 'NewsPosts'
CREATE TABLE [dbo].[NewsPosts] (
    [Id] int IDENTITY(1,1) NOT NULL,
    [Title] nvarchar(max)  NOT NULL,
    [Body] nvarchar(max)  NOT NULL,
    [PostDate] datetime  NOT NULL,
    [Players_Id] int  NOT NULL
);
GO

-- Creating table 'WorkoutPosts'
CREATE TABLE [dbo].[WorkoutPosts] (
    [Id] bigint IDENTITY(1,1) NOT NULL,
    [Body] nvarchar(max)  NOT NULL,
    [PostDate] datetime  NOT NULL,
    [Player_Id] int  NOT NULL
);
GO

-- Creating table 'PlayerTeam'
CREATE TABLE [dbo].[PlayerTeam] (
    [Player_Id] int  NOT NULL,
    [Teams_Id] int  NOT NULL
);
GO

-- Creating table 'NewsPostTeam'
CREATE TABLE [dbo].[NewsPostTeam] (
    [NewsPost_Id] int  NOT NULL,
    [Teams_Id] int  NOT NULL
);
GO

-- Creating table 'TeamWorkoutPost'
CREATE TABLE [dbo].[TeamWorkoutPost] (
    [Team_Id] int  NOT NULL,
    [WorkoutPosts_Id] bigint  NOT NULL
);
GO

-- --------------------------------------------------
-- Creating all PRIMARY KEY constraints
-- --------------------------------------------------

-- Creating primary key on [Id] in table 'Teams'
ALTER TABLE [dbo].[Teams]
ADD CONSTRAINT [PK_Teams]
    PRIMARY KEY CLUSTERED ([Id] ASC);
GO

-- Creating primary key on [Id] in table 'Players'
ALTER TABLE [dbo].[Players]
ADD CONSTRAINT [PK_Players]
    PRIMARY KEY CLUSTERED ([Id] ASC);
GO

-- Creating primary key on [Id] in table 'PlayerTeamPermissions'
ALTER TABLE [dbo].[PlayerTeamPermissions]
ADD CONSTRAINT [PK_PlayerTeamPermissions]
    PRIMARY KEY CLUSTERED ([Id] ASC);
GO

-- Creating primary key on [Id] in table 'NewsPosts'
ALTER TABLE [dbo].[NewsPosts]
ADD CONSTRAINT [PK_NewsPosts]
    PRIMARY KEY CLUSTERED ([Id] ASC);
GO

-- Creating primary key on [Id] in table 'WorkoutPosts'
ALTER TABLE [dbo].[WorkoutPosts]
ADD CONSTRAINT [PK_WorkoutPosts]
    PRIMARY KEY CLUSTERED ([Id] ASC);
GO

-- Creating primary key on [Player_Id], [Teams_Id] in table 'PlayerTeam'
ALTER TABLE [dbo].[PlayerTeam]
ADD CONSTRAINT [PK_PlayerTeam]
    PRIMARY KEY CLUSTERED ([Player_Id], [Teams_Id] ASC);
GO

-- Creating primary key on [NewsPost_Id], [Teams_Id] in table 'NewsPostTeam'
ALTER TABLE [dbo].[NewsPostTeam]
ADD CONSTRAINT [PK_NewsPostTeam]
    PRIMARY KEY CLUSTERED ([NewsPost_Id], [Teams_Id] ASC);
GO

-- Creating primary key on [Team_Id], [WorkoutPosts_Id] in table 'TeamWorkoutPost'
ALTER TABLE [dbo].[TeamWorkoutPost]
ADD CONSTRAINT [PK_TeamWorkoutPost]
    PRIMARY KEY CLUSTERED ([Team_Id], [WorkoutPosts_Id] ASC);
GO

-- --------------------------------------------------
-- Creating all FOREIGN KEY constraints
-- --------------------------------------------------

-- Creating foreign key on [Player_Id] in table 'PlayerTeam'
ALTER TABLE [dbo].[PlayerTeam]
ADD CONSTRAINT [FK_PlayerTeam_Player]
    FOREIGN KEY ([Player_Id])
    REFERENCES [dbo].[Players]
        ([Id])
    ON DELETE NO ACTION ON UPDATE NO ACTION;
GO

-- Creating foreign key on [Teams_Id] in table 'PlayerTeam'
ALTER TABLE [dbo].[PlayerTeam]
ADD CONSTRAINT [FK_PlayerTeam_Team]
    FOREIGN KEY ([Teams_Id])
    REFERENCES [dbo].[Teams]
        ([Id])
    ON DELETE NO ACTION ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_PlayerTeam_Team'
CREATE INDEX [IX_FK_PlayerTeam_Team]
ON [dbo].[PlayerTeam]
    ([Teams_Id]);
GO

-- Creating foreign key on [Players_Id] in table 'PlayerTeamPermissions'
ALTER TABLE [dbo].[PlayerTeamPermissions]
ADD CONSTRAINT [FK_PlayerTeamPermissionsPlayer]
    FOREIGN KEY ([Players_Id])
    REFERENCES [dbo].[Players]
        ([Id])
    ON DELETE CASCADE ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_PlayerTeamPermissionsPlayer'
CREATE INDEX [IX_FK_PlayerTeamPermissionsPlayer]
ON [dbo].[PlayerTeamPermissions]
    ([Players_Id]);
GO

-- Creating foreign key on [Teams_Id] in table 'PlayerTeamPermissions'
ALTER TABLE [dbo].[PlayerTeamPermissions]
ADD CONSTRAINT [FK_PlayerTeamPermissionsTeam]
    FOREIGN KEY ([Teams_Id])
    REFERENCES [dbo].[Teams]
        ([Id])
    ON DELETE CASCADE ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_PlayerTeamPermissionsTeam'
CREATE INDEX [IX_FK_PlayerTeamPermissionsTeam]
ON [dbo].[PlayerTeamPermissions]
    ([Teams_Id]);
GO

-- Creating foreign key on [Players_Id] in table 'NewsPosts'
ALTER TABLE [dbo].[NewsPosts]
ADD CONSTRAINT [FK_NewsPostPlayer]
    FOREIGN KEY ([Players_Id])
    REFERENCES [dbo].[Players]
        ([Id])
    ON DELETE CASCADE ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_NewsPostPlayer'
CREATE INDEX [IX_FK_NewsPostPlayer]
ON [dbo].[NewsPosts]
    ([Players_Id]);
GO

-- Creating foreign key on [NewsPost_Id] in table 'NewsPostTeam'
ALTER TABLE [dbo].[NewsPostTeam]
ADD CONSTRAINT [FK_NewsPostTeam_NewsPost]
    FOREIGN KEY ([NewsPost_Id])
    REFERENCES [dbo].[NewsPosts]
        ([Id])
    ON DELETE NO ACTION ON UPDATE NO ACTION;
GO

-- Creating foreign key on [Teams_Id] in table 'NewsPostTeam'
ALTER TABLE [dbo].[NewsPostTeam]
ADD CONSTRAINT [FK_NewsPostTeam_Team]
    FOREIGN KEY ([Teams_Id])
    REFERENCES [dbo].[Teams]
        ([Id])
    ON DELETE NO ACTION ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_NewsPostTeam_Team'
CREATE INDEX [IX_FK_NewsPostTeam_Team]
ON [dbo].[NewsPostTeam]
    ([Teams_Id]);
GO

-- Creating foreign key on [Player_Id] in table 'WorkoutPosts'
ALTER TABLE [dbo].[WorkoutPosts]
ADD CONSTRAINT [FK_PlayerWorkoutPost]
    FOREIGN KEY ([Player_Id])
    REFERENCES [dbo].[Players]
        ([Id])
    ON DELETE CASCADE ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_PlayerWorkoutPost'
CREATE INDEX [IX_FK_PlayerWorkoutPost]
ON [dbo].[WorkoutPosts]
    ([Player_Id]);
GO

-- Creating foreign key on [Team_Id] in table 'TeamWorkoutPost'
ALTER TABLE [dbo].[TeamWorkoutPost]
ADD CONSTRAINT [FK_TeamWorkoutPost_Team]
    FOREIGN KEY ([Team_Id])
    REFERENCES [dbo].[Teams]
        ([Id])
    ON DELETE NO ACTION ON UPDATE NO ACTION;
GO

-- Creating foreign key on [WorkoutPosts_Id] in table 'TeamWorkoutPost'
ALTER TABLE [dbo].[TeamWorkoutPost]
ADD CONSTRAINT [FK_TeamWorkoutPost_WorkoutPost]
    FOREIGN KEY ([WorkoutPosts_Id])
    REFERENCES [dbo].[WorkoutPosts]
        ([Id])
    ON DELETE NO ACTION ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_TeamWorkoutPost_WorkoutPost'
CREATE INDEX [IX_FK_TeamWorkoutPost_WorkoutPost]
ON [dbo].[TeamWorkoutPost]
    ([WorkoutPosts_Id]);
GO

-- --------------------------------------------------
-- Script has ended
-- --------------------------------------------------