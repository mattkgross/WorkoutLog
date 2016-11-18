SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Matthew Gross
-- Create date: 11/16/16
-- Description:	Creates a new user.
-- =============================================
CREATE PROCEDURE CreateUser
	@firstname nvarchar(500),
	@lastname nvarchar(500),
	@email nvarchar(500),
	@login_type int = 0
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    INSERT INTO users (firstname, lastname, email, login_type)
	VALUES (@firstname, @lastname, @email, @login_type)

	SELECT SCOPE_IDENTITY() as user_id

    RETURN
END
GO
