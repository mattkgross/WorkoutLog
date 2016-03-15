using Microsoft.Owin;
using Owin;

[assembly: OwinStartupAttribute(typeof(WorkoutLogger.Startup))]
namespace WorkoutLogger
{
    public partial class Startup
    {
        public void Configuration(IAppBuilder app)
        {
            ConfigureAuth(app);
        }
    }
}
