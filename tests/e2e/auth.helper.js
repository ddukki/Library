export async function registerUser(page, email = null) {
    const userEmail = email || `test-${Date.now()}@example.com`;
    const password = 'password123';

    await page.goto('/register', { waitUntil: 'networkidle' });
    await page.getByLabel('Name').fill('Test User');
    await page.getByLabel('E-Mail Address').fill(userEmail);
    await page.getByLabel('Password', { exact: true }).fill(password);
    await page.getByLabel('Confirm Password').fill(password);
    await page.getByRole('button', { name: 'Register' }).click();

    await page.waitForURL(/\/home/, { timeout: 30000 });
}
