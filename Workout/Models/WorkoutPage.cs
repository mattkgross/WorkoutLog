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

        #endregion Properties

        #region Methods

        /// <summary>
        /// Sets the width of the page to a certain percent.
        /// </summary>
        /// <param name="percentage"></param>
        public void SetWidthPercentage(double percentage)
        {
            this.Master.ContainerStyle.Add(HtmlTextWriterStyle.Width, string.Format("{0}%", percentage));
        }

        /// <summary>
        /// Sets the height of the page to a certain percent.
        /// </summary>
        /// <param name="percentage"></param>
        public void SetHeightPercentage(double percentage)
        {
            this.Master.ContainerStyle.Add(HtmlTextWriterStyle.Height, string.Format("{0}%", percentage));
        }

        #endregion Methods
    }
}