using Microsoft.Owin;
using Owin;

[assembly: OwinStartupAttribute(typeof(WorkoutLog.Startup))]
namespace WorkoutLog
{
    public partial class Startup {
        public void Configuration(IAppBuilder app) {
            ConfigureAuth(app);
        }
    }
}
