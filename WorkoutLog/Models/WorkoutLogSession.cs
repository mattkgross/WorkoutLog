using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Principal;
using Microsoft.AspNet.Identity;
using Microsoft.AspNet.Identity.Owin;
using Microsoft.Owin.Security;
using System.Text;
using System.Threading.Tasks;
using System.Web;
using WorkoutLog.Models.DataModels;

namespace WorkoutLog.Models
{
    public class WorkoutLogSession
    {
        /// <summary>
        /// Will initiate an Empty session. You must set a User to populate other fields.
        /// </summary>
        public WorkoutLogSession()
        {
            Reload();
        }

        public bool IsLoaded { get; private set; } = false; // C# 6, baby.

        public bool EnsureLoaded()
        {
            if (!IsLoaded)
            {
                Reload();
            }

            // Did ensure succeed?
            return IsLoaded;
        }

        private void Reload()
        {
            IsLoaded = true;
            LoadUserDependentObjects();
        }

        private void LoadUserDependentObjects()
        {
            if (user == null)
            {
                IsLoaded = false;
                return;
            }

            using (var conn = new MasterContainer())
            {
                // Load corresponding player. No player means no user, no user means meaningless session.
                player = null;
                if (conn.Players.Count() > 0)
                {
                    player = conn.Players.First<Player>(p => p.UserId.Equals(user.Id));
                }
                if (player == null) IsLoaded = false;

                // Load first team (if any).
                if (player != null && player.Teams.Count() > 0)
                {
                    team = player.Teams.First<Team>(t => t.Id == player.Id);
                }
            }
        }

        #region Properties

        private ApplicationUser user = null;
        public ApplicationUser User
        {
            get
            {
                EnsureLoaded();
                return user;
            }

            set
            {
                user = value;
                Reload();
            }
        }

        private Player player = null;
        public Player Player
        {
            get
            {
                EnsureLoaded();
                return player;
            }
        }

        private Team team = null;
        public Team Team
        {
            get
            {
                EnsureLoaded();
                return team;
            }

            set
            {
                // Make sure we have a player and it's a valid team.
                if ((player != null) && player.Teams.Contains(value))
                {
                    team = value;
                }
            }
        }

        #endregion Properties
    }
}
