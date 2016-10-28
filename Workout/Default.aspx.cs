using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace Workout
{
    public partial class _Default : Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Set container width to 40%;
            SiteMaster master = this.Master as SiteMaster;
            master?.ContainerStyle.Add(HtmlTextWriterStyle.Width, "40%");
        }
    }
}