using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace Workout.App_UserControls
{
    public partial class ucDimensionalUc : System.Web.UI.UserControl
    {
        public double Width
        {
            get
            {
                return double.Parse(HttpContext.Current.Request.Params[clientScreenWidth.ClientID]);
            }
        }

        public double Height
        {
            get
            {
                return double.Parse(HttpContext.Current.Request.Params[clientScreenHeight.ClientID]);
            }
        }

        protected void Page_Load(object sender, EventArgs e)
        {
        }
    }
}