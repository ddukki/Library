import { test, expect } from '@playwright/test';
import { registerUser } from './auth.helper';

test.describe('Shelf Manager', () => {
    test.describe.configure({ mode: 'serial' });

    let page;

    test.beforeAll(async ({ browser }) => {
        test.setTimeout(120000);
        const context = await browser.newContext();
        page = await context.newPage();
        await registerUser(page);
    });

    test('shows empty state when no shelves exist', async () => {
        await expect(page.getByRole('heading', { name: 'Your Shelves' })).toBeVisible();
        await expect(page.getByRole('link', { name: /Add New Shelf/ })).toBeVisible();
    });

    test('can create a shelf and then delete it', async () => {
        await page.goto('/library/shelves/create', { waitUntil: 'networkidle' });
        await page.getByLabel('Shelf Name').fill('Test Shelf');
        await page.getByRole('button', { name: 'Add' }).click();

        await page.waitForURL(/\/library\/shelves\/\d+/);

        await page.goto('/home', { waitUntil: 'networkidle' });
        await expect(page.getByText('Test Shelf').first()).toBeVisible();

        await page.locator('[aria-label="Delete shelf"]').click();

        await expect(page.getByText('Test Shelf')).not.toBeVisible();
    });
});
