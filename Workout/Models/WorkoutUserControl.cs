using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace Workout.Models
{
    public partial class WorkoutUserControl : System.Web.UI.UserControl
    {
        public Workout.Models.Context WorkoutContext
        {
            get
            {
                Workout.Models.Context context = (this.Page as WorkoutPage).WorkoutContext;

                if(context == null)
                {
                    throw new ApplicationException("Unable to retrieve parent page's WorkoutContext from user control.");
                }

                return context;
            }
        }
    }
}