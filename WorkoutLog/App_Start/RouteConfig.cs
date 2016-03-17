using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Routing;

namespace WorkoutLog
{
    public class RouteConfig
    {
        public static void RegisterRoutes(RouteCollection routes)
        {
            // Do not route direct requests for system resources to a controller.
            routes.IgnoreRoute("{resource}.axd/{*pathInfo}");

            // Simply to test out routing, adding easter egg here.
            // This will direct to an Action in the Home controller that will return
            // raw content, rather than a view.
            routes.MapRoute(
                name: "EasterEgg",
                url: "EasterEgg/{text}",
                defaults: new { controller = "Home", action = "EasterEgg", text = "found" }
                );

            // Routing schema for URLs.
            routes.MapRoute(
                name: "Default",
                url: "{controller}/{action}/{id}",
                defaults: new { controller = "Home", action = "Index", id = UrlParameter.Optional }
            );
        }
    }
}
