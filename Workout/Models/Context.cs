using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using BusinessObjects;

namespace Workout.Models
{
    public class Context
    {
        public User User
        {
            get; private set;
        }

        public bool LoggedIn
        {
            get
            {
                return this.User != null;
            }
        }

        public void LoadUser(User user)
        {
            User = user;
        }
    }
}