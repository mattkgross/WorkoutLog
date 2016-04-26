using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace WorkoutLog.Models
{
    public class TeamUser
    {
        public int Id { get; set; }
        public string UserId { get; set; }
        public int TeamId { get; set; }
        public bool AdminRights { get; set; }
    }

    public class TeamUserDBContext : DbContext
    {
        public DbSet<TeamUser> TeamUsers { get; set; }

        public TeamUserDBContext()
            : base("DefaultConnection")
        {
        }

        public static TeamUserDBContext Create()
        {
            return new TeamUserDBContext();
        }
    }
}
