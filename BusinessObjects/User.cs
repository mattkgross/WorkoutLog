using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using BusinessObjects.Database;

namespace BusinessObjects
{
    public class User
    {
        private bool isLoaded = false;

        private User(int id)
        {
            this.id = id;
            EnsureLoaded();
        }

        public static User CreateUser(UserType loginType)
        {
            string firstname, lastname, email;

            ExtractSSOMetaData(out firstname, out lastname, out email);

            DataTable db = DBHelper.ExecuteProcedure("CreateUser",
                firstname,
                lastname,
                email,
                ((int)loginType).ToString());

            int id = int.Parse(db.Rows[0].Field<Decimal>("user_id").ToString());
            return User.LoadFromId(id);
        }

        public static User LoadFromId(int id)
        {
            return new User(id);
        }

        protected void EnsureLoaded()
        {
            if(isLoaded)
            {
                return;
            }

            string sql = string.Format("SELECT * FROM users WHERE user_id={0}", id);
            DataRow data = DBHelper.ExecuteQuery(sql).Rows[0];

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
            email = "mattkgross@gmail.com";
        }

        #region Properties

        private int id;
        public int UserId
        {
            get
            {
                EnsureLoaded();
                return this.id;
            }
        }

        private string firstname;
        public string FirstName
        {
            get
            {
                EnsureLoaded();
                return this.firstname;
            }
        }

        private string lastname;
        public string LastName
        {
            get
            {
                EnsureLoaded();
                return this.lastname;
            }
        }

        private string email;
        public string Email
        {
            get
            {
                EnsureLoaded();
                return this.email;
            }
        }

        private UserType loginType;
        public UserType LoginType
        {
            get
            {
                EnsureLoaded();
                return loginType;
            }
        }

        #endregion Properties
    }
}
