using Microsoft.Owin;
using Owin;

[assembly: OwinStartupAttribute(typeof(Workout.Startup))]
namespace Workout
{
    public partial class Startup {
        public void Configuration(IAppBuilder app) {
            ConfigureAuth(app);
        }
    }
}
