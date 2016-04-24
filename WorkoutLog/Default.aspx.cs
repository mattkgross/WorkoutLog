using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace WorkoutLog
{
    public partial class _Default : Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

        }

        protected void RegisterButton_Click(object sender, EventArgs e)
        {
            // https://stackoverflow.com/questions/224569/server-transfer-vs-response-redirect
            Response.Redirect("~/Account/Register");
        }
    }
}