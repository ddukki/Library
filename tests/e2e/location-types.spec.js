import { test, expect } from '@playwright/test';
import { registerUser } from './auth.helper';

test.describe('Location Types', () => {
    test.describe.configure({ mode: 'serial' });

    let page;

    test.beforeAll(async ({ browser }) => {
        test.setTimeout(120000);
        const context = await browser.newContext();
        page = await context.newPage();
        await registerUser(page);
    });

    test('shows empty state when no location types exist', async () => {
        await page.goto('/library/locationtypes');
        await expect(page.getByText(/Add.*Location Types/)).toBeVisible();
    });

    test('can add a location type', async () => {
        await page.goto('/library/locationtypes');
        await page.getByLabel('New location type').fill('Hardcover');
        await page.getByRole('button', { name: 'Add' }).click();
        await expect(page.locator('table')).toContainText('Hardcover');
    });

    test('can delete a location type', async () => {
        await page.goto('/library/locationtypes');

        await page.getByLabel('New location type').fill('Paperback');
        await page.getByRole('button', { name: 'Add' }).click();
        await expect(page.locator('table')).toContainText('Paperback');

        const row = page.locator('tr:has(td:text("Paperback"))');
        await row.getByRole('button', { name: 'Delete location type' }).click();

        await expect(page.locator('table')).not.toContainText('Paperback');
    });
});
