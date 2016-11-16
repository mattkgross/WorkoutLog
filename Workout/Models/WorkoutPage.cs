using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;

namespace Workout.Models
{
    public class WorkoutPage : Page
    {
        #region Properties

        /// <summary>
        /// Gets the page's MasterPage.
        /// </summary>
        public new SiteMaster Master
        {
            get
            {
                // Get the Page's Master, which should be our SiteMaster.
                SiteMaster master = (this as Page).Master as SiteMaster;

                if(master == null)
                {
                    throw new ApplicationException("Master Page could not be found.");
                }

                return master;
            }
        }

        public Context WorkoutContext
        {
            get
            {
                if(this.Session["WorkoutContext"] == null)
                {
                    this.Session["WorkoutContext"] = new Context();
                }

                return this.Session["WorkoutContext"] as Context;
            }
        }

        #endregion Properties

        #region Methods

        public bool RequiresLogin()
        {
            return false;
        }

        #endregion Methods
    }
}