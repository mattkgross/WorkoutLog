using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace WorkoutLog.Team.uc
{
    public partial class uc_CreateTeam : System.Web.UI.UserControl
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Populate placeholders.
            TeamName.Attributes.Add("placeholder", "Team Name");
            EnrollKey.Attributes.Add("placeholder", "(Optional) Enrollment Key");
        }

        // Post back to create new team.
        protected void CreateTeamButton_Click(object sender, EventArgs e)
        {

        }
    }
}