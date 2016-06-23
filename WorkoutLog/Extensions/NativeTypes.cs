using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using WorkoutLog.Models;

namespace WorkoutLog.Extensions
{
    public static class NativeTypes
    {
        public static WorkoutLogSession GetSessionObject(this HttpContext current)
        {
            return current != null ? (WorkoutLogSession)current.Session["__WorkoutLogSession"] : null;
        }

        public static void UpdateSession(this ApplicationSignInManager current, ApplicationUser user)
        {
            HttpContext.Current.GetSessionObject().User = user;
            HttpContext.Current.GetSessionObject().EnsureLoaded();
        }
    }
}