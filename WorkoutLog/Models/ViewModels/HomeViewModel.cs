using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using WorkoutLog.Extensions;

namespace WorkoutLog.Models.ViewModels
{
    public class HomeViewModel
    {
        private WorkoutLogSession session = HttpContext.Current.GetSessionObject();
        public WorkoutLogSession Session { get { return session; } }

        public string FirstName { get { return Session.Player?.FirstName; } }
    }
}