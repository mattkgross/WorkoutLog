using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Web;
using WorkoutLog.Extensions;

namespace WorkoutLog.Models.ViewModels
{
    public class NavbarViewModel
    {
        private WorkoutLogSession session = HttpContext.Current.GetSessionObject();

        public string FirstName
        {
            get
            {
                if (session.IsLoaded)
                {
                    return session.Player.FirstName;
                }

                return "Guest";
            }
        }

        public string LastName
        {
            get
            {
                if(session.IsLoaded)
                {
                    return session.Player.LastName;
                }

                return string.Empty;
            }
        }
    }
}
