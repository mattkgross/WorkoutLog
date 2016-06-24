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
            if (current != null)
            {
                WorkoutLogSession session = current.Session["__WorkoutLogSession"] as WorkoutLogSession;
                session.EnsureLoaded();
                return session;
            }
            return null;
        }

        public static void UpdateSession(this ApplicationSignInManager current, ApplicationUser user)
        {
            HttpContext.Current.GetSessionObject().User = user;
            HttpContext.Current.GetSessionObject().EnsureLoaded();
        }
    }
}