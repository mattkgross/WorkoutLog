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
    }
}