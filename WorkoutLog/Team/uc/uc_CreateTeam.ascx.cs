using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Microsoft.AspNet.Identity;
using WorkoutLog.Extensions;
using WorkoutLog.Models;

namespace WorkoutLog.Team.uc
{
    public partial class uc_CreateTeam : System.Web.UI.UserControl
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Populate placeholders.
            TeamName.Attributes.Add("placeholder", "Team Name");
            EnrollKey.Attributes.Add("placeholder", "Enrollment Key (Optional)");
            // Disable autocomplete.
            TeamName.Attributes.Add("autocomplete", "new-password");
            EnrollKey.Attributes.Add("autocomplete", "new-password");
        }

        // Post back to create new team.
        protected void CreateTeamButton_Click(object sender, EventArgs e)
        {
            string teamName, enrollKey;
            bool shouldAdd = ValidateNewTeamRequest(out teamName, out enrollKey);

            if (shouldAdd)
            {
                using (var db = new TeamDBContext())
                {
                    var obj = db.Teams.Add(new Models.Team{ Name = teamName, EnrollmentKey = (enrollKey == string.Empty) ? null : enrollKey, DateCreated = DateTime.Now });
                    int relate = db.SaveChanges();

                    // If insert succeeded, create relationship.
                    if (relate == 1)
                    {
                        using (var db2 = new TeamUserDBContext())
                        {
                            var obj2 = db2.TeamUsers.Add(new Models.TeamUser{ TeamId = obj.Id, UserId = Context.User.Identity.GetUserId(), AdminRights = true });
                            int relate2 = db2.SaveChanges();

                            // If successful, load the team as the current team.
                            if (relate2 == 1)
                            {
                                Session["Team"] = obj;
                            }
                            else
                            {
                                // Display error.
                            }
                        }
                    }
                    else
                    {
                        // Display error.
                    }
                }
            }
        }

        protected bool ValidateNewTeamRequest(out string teamName, out string enrollKey)
        {
            bool retval = true;
            teamName = TeamName.Text;
            enrollKey = EnrollKey.Text;

            // TODO: Future verification.
            if (enrollKey.Length == 0)
            {
                // Redundant, but let's keep it here.
                enrollKey = string.Empty;
            }

            return retval;
        }
    }
}