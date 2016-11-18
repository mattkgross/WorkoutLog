using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using DotNetOpenAuth.OpenId;
using DotNetOpenAuth.OpenId.RelyingParty;
using BusinessObjects;
using System.Data;
using Workout.Models;

namespace Workout.App_UserControls
{
    public partial class ucLogin : WorkoutUserControl
    {
        #region Properties

        #endregion Properties

        #region Page Events

        protected void Page_Load(object sender, EventArgs e)
        {
            CheckLoginStatus();
        }

        protected void CheckLoginStatus()
        {
            
        }

        #endregion Page Events

        protected void lnkGoogle_Click(object sender, EventArgs e)
        {
            CreateOrLoginUser(UserType.Google);
        }

        protected void lnkFacebook_Click(object sender, EventArgs e)
        {
            CreateOrLoginUser(UserType.Facebook);
        }

        private void CreateOrLoginUser(UserType type)
        {
            // Check if user already exists, if so then load, else create.
            //string someIdentifier;
            int exists = 0;//DBHelper.ExecuteProcedure("UserExists", someIdentifier).Rows[0].Field<bool>("user_id");

            User user;
            if(exists == 0)
            {
                user = User.CreateUser(type);
            }
            else
            {
                user = User.LoadFromId(exists);
            }

            this.WorkoutContext.LoadUser(user);
        }
    }
}