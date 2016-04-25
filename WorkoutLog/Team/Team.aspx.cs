using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
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
            }
        }
    }
}