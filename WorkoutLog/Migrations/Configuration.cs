namespace WorkoutLog.Migrations
{
    using System;
    using System.Data.Entity;
    using System.Data.Entity.Migrations;
    using System.Linq;

    internal sealed class Configuration : DbMigrationsConfiguration<WorkoutLog.Models.ApplicationDbContext>
    {
        public Configuration()
        {
            // See https://msdn.microsoft.com/en-us/data/jj591621 for how to create a migration.
            AutomaticMigrationsEnabled = false;
            ContextKey = "WorkoutLog.Models.ApplicationDbContext";
        }

        protected override void Seed(WorkoutLog.Models.ApplicationDbContext context)
        {
            //  This method will be called after migrating to the latest version.

            //  You can use the DbSet<T>.AddOrUpdate() helper extension method 
            //  to avoid creating duplicate seed data. E.g.
            //
            //    context.People.AddOrUpdate(
            //      p => p.FullName,
            //      new Person { FullName = "Andrew Peters" },
            //      new Person { FullName = "Brice Lambson" },
            //      new Person { FullName = "Rowan Miller" }
            //    );
            //
        }
    }
}
