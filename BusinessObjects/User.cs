using Helpers;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BusinessObjects
{
    public class User
    {
        private bool isLoaded = false;

        private User() { }

        public static User CreateUser(UserType loginType)
        {
            string firstname, lastname, email;

            ExtractSSOMetaData(out firstname, out lastname, out email);

            DataTable db = DBHelper.ExecuteProcedure("CreateUser",
                firstname,
                lastname,
                email,
                (int)loginType);

            int id = db.Rows[0].Field<int>("user_id");
            return User.LoadFromId(id);
        }

        public static User LoadFromId(int id)
        {
            User user = new User();
            user.EnsureLoaded();

            return user;
        }

        protected void EnsureLoaded()
        {
            string sql = string.Format("SELECT * FROM users WHERE user_id={0}", id);
            DataRow data = DBHelper.ExecuteQuery(sql).Rows[0];

            this.id = data.Field<int>("user_id");
            this.firstname = data.Field<string>("firstname");
            this.lastname = data.Field<string>("lastname");
            this.email = data.Field<string>("email");
            this.loginType = (UserType)data.Field<int>("login_type");

            isLoaded = true;
        }

        protected static void ExtractSSOMetaData(out string firstname,out string lastname, out string email)
        {
            firstname = "Matt";
            lastname = "Gross";
            email = "mattkgrossgmailcom";
        }

        #region Properties

        private int id;
        public int UserId
        {
            get
            {
                if(!isLoaded)
                {
                    EnsureLoaded();
                }
                return this.id;
            }
        }

        private string firstname;
        public string FirstName
        {
            get
            {
                if (!isLoaded)
                {
                    EnsureLoaded();
                }
                return this.firstname;
            }
        }

        private string lastname;
        public string LastName
        {
            get
            {
                if (!isLoaded)
                {
                    EnsureLoaded();
                }
                return this.lastname;
            }
        }

        private string email;
        public string Email
        {
            get
            {
                if (!isLoaded)
                {
                    EnsureLoaded();
                }
                return this.email;
            }
        }

        private UserType loginType;
        public UserType LoginType
        {
            get
            {
                if (!isLoaded)
                {
                    EnsureLoaded();
                }
                return loginType;
            }
        }

        #endregion Properties
    }
}
