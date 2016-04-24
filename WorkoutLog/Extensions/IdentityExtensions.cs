using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Claims;
using System.Security.Principal;
using System.Web;


namespace WorkoutLog.Extensions
{
    public static class IdentityExtensions
    {
        public static string GetFirstName(this IIdentity identity)
        {
            return ClaimFactory((ClaimsIdentity)identity, "FirstName");
        }

        public static string GetLastName(this IIdentity identity)
        {
            return ClaimFactory((ClaimsIdentity)identity, "LastName");
        }

        public static string GetFullName(this IIdentity identity)
        {
            return GetFirstName(identity) + " " + GetLastName(identity);
        }

        private static string ClaimFactory(ClaimsIdentity identity, string identityName)
        {
            var claim = ((ClaimsIdentity)identity).FindFirst(identityName);
            return (claim == null) ? string.Empty : claim.Value;
        }
    }
}