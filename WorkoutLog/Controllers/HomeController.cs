using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace WorkoutLog.Controllers
{
    public class HomeController : Controller
    {
        // GET /home/index
        public ActionResult Index()
        {
            return View();
        }

        // GET /home/about
        public ActionResult About()
        {
            return View();
        }

        // GET /EasterEgg/{optional text string}
        public ActionResult EasterEgg(string text)
        {
            var data = "What were you trying to do with my egg?!";

            if (text == "found")
            {
                data = "You found the egg!";
            }
            else if(text == "cracked")
            {
                data = "You cracked the egg!";
            }

            return Content(data);
        }
    }
}