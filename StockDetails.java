package com.example.shalini.stockapp;
import android.content.Context;
import android.content.Intent;
import android.support.design.widget.TabLayout;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;

public class StockDetails extends AppCompatActivity {
    TabLayout objTabLayout;
    ViewPager objViewPager;

    public boolean onCreateOptionsMenu(Menu menu) {

        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menuitems, menu);
        return true;}


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_stock_details);

        objViewPager = (ViewPager) findViewById(R.id.pager);
        objViewPager.setAdapter(new CustomAdapter(getSupportFragmentManager(), getApplicationContext()));

        objTabLayout = (TabLayout) findViewById(R.id.tab_layout);
        objTabLayout.setupWithViewPager(objViewPager);
        objTabLayout.setOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                objViewPager.setCurrentItem(tab.getPosition());
            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {
                objViewPager.setCurrentItem(tab.getPosition());
            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {
                objViewPager.setCurrentItem(tab.getPosition());
            }
        });
    }


    private class CustomAdapter extends FragmentPagerAdapter {
        private String fragements[] = {"Stock Details", "Charts", "News"};

        public CustomAdapter(android.support.v4.app.FragmentManager supportFragmentManager, Context applicationContext) {
            super(supportFragmentManager);
        }

        @Override
        public android.support.v4.app.Fragment getItem(int position) {
            switch (position) {
                case 0:
                    return new CompanyDetails();
                case 1:
                    return new HistoricalCharts();
                case 2:
                    return new NewsFeed();
                default:
                    return null;
            }
        }

        @Override
        public int getCount() {
            return fragements.length;
        }

        @Override
        public CharSequence getPageTitle(int position) {
            return fragements[position];
        }



    }
}



