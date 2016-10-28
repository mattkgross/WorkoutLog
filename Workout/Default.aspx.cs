using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Workout.Models;

namespace Workout
{
    public partial class _Default : WorkoutPage
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Set container width to 40% for desktop.
            if (!Request.Browser.IsMobileDevice)
            {
                this.SetWidthPercentage(40);
            }
        }
    }
}