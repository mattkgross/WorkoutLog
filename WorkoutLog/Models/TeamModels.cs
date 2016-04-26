using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace WorkoutLog.Models
{
    public class Team
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public string EnrollmentKey { get; set; }
        public DateTime DateCreated { get; set; }
    }

    public class TeamDBContext : DbContext
    {
        public DbSet<Team> Teams { get; set; }

        public TeamDBContext()
            : base("DefaultConnection")
        {
        }

        public static TeamDBContext Create()
        {
            return new TeamDBContext();
        }
    }
}
