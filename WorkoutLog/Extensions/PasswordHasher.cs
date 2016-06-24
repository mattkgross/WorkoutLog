using Microsoft.AspNet.Cryptography.KeyDerivation;
using System;
using System.Collections.Generic;
using System.Security.Cryptography;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace WorkoutLog.Extensions
{
    public static class PasswordHasher
    {
        public static string HashPassword(string password)
        {
            // Generate a 256-bit salt using a secure PRNG.
            byte[] salt = new byte[256 / 8];
            using (var rng = RandomNumberGenerator.Create())
            {
                rng.GetBytes(salt);
            }

            // Derive a 512-bit subkey (use HMACSHA512 with 1,000 iterations).
            string hashed = Convert.ToBase64String(KeyDerivation.Pbkdf2(
                password: password,
                salt: salt,
                prf: KeyDerivationPrf.HMACSHA512,
                iterationCount: 1000,
                numBytesRequested: 512 / 8));

            return hashed;
        }
    }
}
