using System;
using System.Linq;
using System.Web;
using System.Web.UI;
using Microsoft.AspNet.Identity;
using Microsoft.AspNet.Identity.Owin;
using Owin;
using WorkoutLog.Models;

namespace WorkoutLog.Account
{
    public partial class Register : Page
    {
        private const int FirstNameMaxLength = 100;
        private const int LastNameMaxLength = 100;
        private const int EmailMaxLength = 256;

        protected void Page_Load(object sender, EventArgs e)
        {
            // Set the placeholders for the form fields.
            FirstName.Attributes.Add("placeholder", "John");
            LastName.Attributes.Add("placeholder", "Doe");
            Email.Attributes.Add("placeholder", "jdoe32@gmail.com");
            Password.Attributes.Add("placeholder", "1 number, 1 lowercase, 1 uppercase");
            ConfirmPassword.Attributes.Add("placeholder", "Be sure it's 10 charcters or more");

            // Set the maxlengths for the form fields.
            FirstName.MaxLength = FirstNameMaxLength;
            LastName.MaxLength = LastNameMaxLength;
            Email.MaxLength = EmailMaxLength;
        }

        protected void CreateUser_Click(object sender, EventArgs e)
        {
            var manager = Context.GetOwinContext().GetUserManager<ApplicationUserManager>();
            var signInManager = Context.GetOwinContext().Get<ApplicationSignInManager>();
            var user = new ApplicationUser() { UserName = Email.Text, Email = Email.Text };
            bool validate = AddUserIdentityProperties(user);

            // If validation failed, then we stop.
            if(!validate)
            {
                return;
            }

            IdentityResult result = manager.Create(user, Password.Text);
            if (result.Succeeded)
            {
                // For more information on how to enable account confirmation and password reset please visit http://go.microsoft.com/fwlink/?LinkID=320771
                //string code = manager.GenerateEmailConfirmationToken(user.Id);
                //string callbackUrl = IdentityHelper.GetUserConfirmationRedirectUrl(code, user.Id, Request);
                //manager.SendEmail(user.Id, "Confirm your account", "Please confirm your account by clicking <a href=\"" + callbackUrl + "\">here</a>.");

                signInManager.SignIn( user, isPersistent: false, rememberBrowser: false);
                IdentityHelper.RedirectToReturnUrl(Request.QueryString["ReturnUrl"], Response);
            }
            else 
            {
                ErrorMessage.Text = result.Errors.FirstOrDefault();
            }
        }

        /// <summary>
        /// Validates the form fields for a new user.
        /// </summary>
        /// <returns>True if all fields pass, false if not.</returns>
        protected bool ValidateUserSubmission()
        {
            bool retval = true;

            // First Name
            if(FirstName.Text.Length > FirstNameMaxLength)
            {
                ErrorMessage.Text = "Weird. Your input seemed mailicious.";
                retval = false;
            }

            // Last Name
            if (LastName.Text.Length > LastNameMaxLength)
            {
                ErrorMessage.Text = "Weird. Your input seemed mailicious.";
                retval = false;
            }

            // Email
            if(Email.Text.Length > EmailMaxLength)
            {
                ErrorMessage.Text = "Weird. Your input seemed mailicious.";
                retval = false;
            }

            return retval;
        }

        /// <summary>
        /// Sets additional (dev added) ApplicationUser properties.
        /// </summary>
        /// <param name="user">The user object to add property values to.</param>
        /// <returns>True if successful, false if not.</returns>
        protected bool AddUserIdentityProperties(ApplicationUser user)
        {
            // If validation failed, then we stop processing.
            if(!ValidateUserSubmission())
            {
                ErrorMessage.Visible = true;
                return false;
            }

            user.FirstName = this.FirstName.Text;
            user.LastName = this.LastName.Text;

            return true;
        }
    }
}