using Microsoft.AspNet.Identity;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using WorkoutLog.Extensions;
using WorkoutLog.Models;

namespace WorkoutLog.Team
{
    public partial class Team : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // If they aren't logged in, they can't access this page.
            if(!User.Identity.IsAuthenticated)
            {
                // Set this as the return url so they come back after logging in.
                Response.Redirect("~/Account/Login?ReturnUrl=~/Team/Team");
                return;
            }

            // If postback, process the data.
            if (Page.IsPostBack)
            {
                // Create team by user.
                //CreateTeam("Sample", "");
                //CreateTeam("Sample2", "nudlyf");
            }
        }

        /// <summary>
        /// Creates a new team.
        /// </summary>
        /// <param name="name">Name of the team.</param>
        /// <param name="enroll_key">Enrollment key.</param>
        /// <returns>True if succeeded, false if not.</returns>
        protected bool CreateTeam(string name, string enroll_key)
        {
            bool retval = true;

            using (var db = new TeamDBContext())
            {
                var obj = db.Teams.Add(new Models.Team {
                    Name = name,
                    EnrollmentKey = (enroll_key == string.Empty) ? null : Extensions.PasswordHasher.HashPassword(enroll_key),
                    DateCreated = DateTime.Now });
                int writes = db.SaveChanges();

                // Check to ensure successful write.
                retval = (writes == 1);

                // If successful create, add the relationship.
                if(retval)
                {
                    retval = CreateTeamUser(obj.Id, User.Identity.GetUserId(), true);
                }
            }

            return retval;
        }

        /// <summary>
        /// Pairs a user to a team.
        /// </summary>
        /// <param name="teamid">ID of the team.</param>
        /// <param name="userid">ID of the user.</param>
        /// <param name="admin">Is the user a team admin?</param>
        /// <returns>True is successful, false if not.</returns>
        protected bool CreateTeamUser(int teamid, string userid, bool admin)
        {
            bool retval = true;

            using (var db = new TeamUserDBContext())
            {
                db.TeamUsers.Add(new Models.TeamUser { TeamId = teamid, UserId = userid, AdminRights = admin });
                int writes = db.SaveChanges();

                // Check to ensure successful write.
                retval = (writes == 1);
            }

            return retval;
        }
    }
}